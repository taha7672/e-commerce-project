<?php

use App\Models\SitesSetting;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Services\IyzipayService;
use App\Models\User;
use App\Models\PaymentGateway;
use App\Models\Payment;

if (!function_exists('generate_replacement_array')) {
    /**
     * Generate an array of replacement values.
     *
     * @param \App\Models\Order $order
     * @return array
     */
    function generate_replacement_array($order): array
    {
        if ($order) {

            $replacements = [
                'USER_NAME' => $order->user()->first()->name,
                'ORDER_ID' => $order->id,
                'ORDER_NUMBER' => $order->order_num,
                'ORDER_AMOUNT' => $order->paid_amount,
                'ORDER_STATUS' => $order->status,
                'VAT_AMOUNT' => $order->vat_amount,
                'DISCOUNT_AMOUNT' => $order->discount_amount,
                'SHIPPING_AMOUNT' => $order->shipping_amount,
                'ORDER_DATE' => date('d.m.Y', strtotime($order->created_at)),
                'SHIPPING_ADDRESS' => get_address_html($order->orderShippingAddress()->first()),
                'BILLING_ADDRESS' => get_address_html($order->orderBillingAddress()->first()),
                'ORDER_ITEMS' => get_order_items_html($order),
                'PAYMENT_METHOD' => $order->payment()->first()->payment_method,
                'DATE' => date('d.m.Y'),
                // 'ORDER_TOTALS' => 'Detailed Order Totals Values.',
                'COMPANY_LOGO' => storage_path("app/" + getSetting('logo_url')),
                'COMPANY_ADDRESS' => getSetting('address'),
                'COMPANY_EMAIL' => getSetting('email'),
                'COMPANY_PHONE' => getSetting('phone_number'),
                'COMPANY_CITY' => getSetting('city'),
                'COMPANY_STATE' => getSetting('state'),
                'COMPANY_NAME' => getSetting('brand_name'),
                'COMPANY_POSTCODE' => getSetting('postal_code'),
                'COMPANY_COUNTRY' => getSetting('country')
            ];
        } else {

            $replacements = [
                'DATE' => date('d.m.Y'),
                'COMPANY_LOGO' => storage_path("app/" + getSetting('logo_url')),
                'COMPANY_ADDRESS' => +getSetting('address'),
                'COMPANY_EMAIL' => +getSetting('email'),
                'COMPANY_PHONE' => +getSetting('phone_number'),
                'COMPANY_CITY' => +getSetting('city'),
                'COMPANY_STATE' => +getSetting('state'),
                'COMPANY_NAME' => +getSetting('brand_name'),
                'COMPANY_POSTCODE' => +getSetting('postal_code'),
                'COMPANY_COUNTRY' => +getSetting('country')
            ];
        }
        return $replacements;
    }
}

function get_address_html($address)
{
    $html = '';
    if ($address) {
        $html .= $address->first_name . ' ' . $address->last_name . ' <br />';
        $html .= $address->email . ' <br />';
        $html .= $address->phone . ' <br />';
        $html .= 'Address : ' . $address->address_line1 . ' ' . $address->address_line2 . ' <br />';
        $html .= $address->city . '/' . $address->state . '/' . $address->country . ' <br />';
        $html .= 'Post Code: ' . $address->postal_code . ' <br />';
    }
    return $html;
}

function get_order_items_html($order)
{
    $html = '';
    $html = view('admin.orders.items', compact('order'))->render();
    return $html;
}


function send_order_status_change_email($order)
{
    // Fetch the template
    $template = EmailTemplate::where('name', 'Status Change Notification')->first();

    if ($template) {

        // Send the email
        try {
            Mail::to($order->user->email)->send(new \App\Mail\CustomEmail($template, $order));
        } catch (\Exception $ex) {
            dd($ex->getMessage());
        }
    }
}

function make_payment_online($order)
{
    $paymentGateway = PaymentGateway::where('is_default', 1)->where('status', 1)->first();
    if ($paymentGateway) {
        $paymentGateway->credentials = json_decode($paymentGateway->credentials, true);
        if ($paymentGateway->type == 'izipay') {
            $iyzipayService = new IyzipayService($paymentGateway->credentials);
            $payment = $iyzipayService->createPayment($order);

            if ($payment->getStatus() == 'success') {
                $payment_data = [
                    'order_id' => $order['basket_id'],
                    'total_amount' => $payment->getPaidPrice(),
                    'payment_method' => $paymentGateway->name,
                    'payment_date' => date('Y-m-d H:i:s'),
                    'payment_status' => $payment->getStatus(),
                    'transaction_id' => $payment->getPaymentId()
                ];
                Payment::create($payment_data);
                return true;
            } else {
                return false;
            }
        } elseif ($paymentGateway->type == 'paddle') {

            $order['billing_address'] = $order['billing_address']->toArray();
            $order['shipping_address'] = $order['shipping_address']->toArray();


            $user = User::find($order['user_id']);
            // paylink variable from controller
            $payment = $user->charge(($order['paid_amount'] * 100), 'test Product', [])->customData(['order_id' => $order['basket_id'], 'input' => $order]);
            $payment_data = array(
                'order_id' => $order['basket_id'],
                'total_amount' => $order['paid_amount'],
                'payment_method' => $paymentGateway->name,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_status' => 'Pending',
                'transaction_id' => $payment->getTransaction()['id']
            );
            Payment::create($payment_data);
            return true;
            // return ($payLink);
            // return $user->checkout('pri_01j2hpkjc14rqm0krzs7kzvyys')
            // ->customData(['order_id' => $order['basket_id'], 'input'=>$order]);
        } elseif ($paymentGateway->type == 'cod' || $paymentGateway->type == 'eft') {
            $payment_data = [
                'order_id' => $order['basket_id'],
                'total_amount' => $order['paid_amount'],
                'payment_method' => $paymentGateway->name,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_status' => 'Pending',
                'transaction_id' => ''
            ];
            Payment::create($payment_data);
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
