<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\SitesSetting;
use Illuminate\Http\Request;
//use App\Services\IyzipayService;
use App\Models\EmailTemplate;
use App\Models\BillingAddress;
use App\Models\ShippingAddress;
use App\Services\IyzipayService;
use Illuminate\Support\Facades\DB;
use App\Models\OrderBillingAddress;
use App\Http\Controllers\Controller;
use App\Models\OrderShippingAddress;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'total_amount' => 'required',
            'shipping_address_id' => 'required',
            'billing_address_id' => 'required',
            'order_items' => 'required',
            'card_holder_name' => 'required',
            'card_number' => 'required',
            'expire_month' => 'required',
            'expire_year' => 'required',
        ], [
            'order_items.required' => 'Order Items are required.',
        ]);
        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        DB::beginTransaction();
        try {

            $input = $request->all();
            $input['status'] = 'pending';

            // Check if user is active
            $user = User::find($input['user_id']);
            if (empty($user)) {
                return $this->errorResponse('User not found', 401);
            } elseif ($user->is_deleted) {
                return $this->errorResponse('User deleted', 401);
            }
            $input['paid_amount'] = $input['total_amount'];

            //  START: Coupon Code
            $input['discount_amount'] = 0;
            if (!empty($input['coupon_code'])) {
                $code = Coupon::where('coupon_code', $input['coupon_code'])->first();
                if ($code) {

                    // Single use Coupon Code Case
                    if ($code->one_time_use) {
                        $alreadyUsed = Order::where('coupon_id', $code->id)
                            ->where('user_id', $input['user_id'])
                            ->first();
                        if (empty($alreadyUsed)) {
                            $discountAmount = $code->discount_type == 'percentage' ? ($input['paid_amount'] * 0.01 * $code->discount_percentage) : $code->discount_amount;
                            $input['paid_amount'] = $input['paid_amount'] - $discountAmount;
                            $input['coupon_id'] = $code->id;
                            $input['discount_amount'] = $discountAmount;
                        }
                    }
                    // Multiple use Coupon Code Case
                    else {
                        $discountAmount = $code->discount_type == 'percentage' ? ($input['paid_amount'] * 0.01 * $code->discount_percentage) : $code->discount_amount;
                        $input['paid_amount'] = $input['paid_amount'] - $discountAmount;
                        $input['coupon_id'] = $code->id;
                        $input['discount_amount'] = $discountAmount;
                    }
                }
            }
            // END: Coupon Code

            // START: Calculate VAT
            $vat = getSetting('vat_amount');
            if (!empty($vat)) {
                $vat_amount = $input['paid_amount'] * 0.01 * $vat;
                $vat_amount = round($vat_amount, 2);
                $input['vat_amount'] = $vat_amount;
                $input['paid_amount'] = $input['paid_amount'] + $vat_amount;
            }
            // END: Calculate VAT

            // START: Calculate Shipping
            $shipping_amount = getSetting('shipping_amount');
            $free_shipping_threshold = getSetting('free_shipping_threshold');
            if (!empty($shipping_amount) && !empty($free_shipping_threshold)) {
                if ($free_shipping_threshold <= $input['paid_amount']) {
                    $shipping_amount = 0;
                }

                $shipping_amount = round($shipping_amount, 2);
                $input['shipping_amount'] = $shipping_amount;
                $input['paid_amount'] = $input['paid_amount'] + $shipping_amount;
            }
            // END: Calculate Shipping

            $order = Order::create($input);
            foreach ($input['order_items'] as $item) {
                // dd($item);
                $data = array();
                $data['order_id'] = $order->id;
                $data['product_id'] = $item['product_id'];
                $data['quantity'] = $item['quantity'];
                $data['price_at_order'] = $item['price_at_order'];
                $data['sub_total'] = ($item['quantity'] * $item['price_at_order']);
                $data['variant_id'] = $item['variant_id'];
                OrderItem::create($data);
            }

            // $iyzipayService = new IyzipayService();
            $input['basket_id'] = $order->id;
            $input['billing_address'] = BillingAddress::find($input['billing_address_id']);
            $input['shipping_address'] = ShippingAddress::find($input['shipping_address_id']);
            $input['ip'] = $request->ip();
            $input['user'] = User::find($input['user_id']);

            $payment = make_payment_online($input);
            if ($payment && gettype($payment) == 'boolean') {

                Notification::create([
                    'user_id' => $request->user_id,
                    'order_id' => $order->id,
                    'message' => "Order #{$order->order_num} has been created",
                ]);

                // Add Payment Data
                // $payment_data = array(
                //     'order_id' => $order->id,
                //     'total_amount' => $payment->getPaidPrice(),
                //     'payment_method' => 'Iyzipay',
                //     'payment_date' => date('Y-m-d H:i:s'),
                //     'payment_status' => $payment->getStatus(),
                //     'transaction_id' => $payment->getPaymentId()
                // );
                // Payment::create($payment_data);

                // $order->status = 'processing';
                // $order->save();

                // // Add Order Status
                // OrderStatus::create([
                //     'order_id' => $order->id,
                //     'status' => 'processing',
                //     'status_date' => date('Y-m-d H:i:s'),
                //     'location' => ''
                // ]);

                // Add Order Addresses
                $billing_data = $input['billing_address']->toArray();
                $billing_data['order_id'] = $order->id;
                OrderBillingAddress::create($billing_data);

                $shipping_data = $input['shipping_address']->toArray();
                $shipping_data['order_id'] = $order->id;
                OrderShippingAddress::create($shipping_data);

                DB::commit();


                // Fetch the template
                $template = EmailTemplate::where('name', 'New Order Notification')->first();
                if (!$template) {
                    return $this->errorResponse('Email Template Not Found!', 401);
                }

                try {
                    Mail::to($order->user->email)->send(new \App\Mail\CustomEmail($template, $order));
                } catch (\Exception $ex) {
                }

                return $this->successResponse($order, 'Order created successfully');
            } elseif ($payment == false) {
                DB::rollBack();
                return $this->errorResponse('Unable to make payment', 401);
            } else {
                return $this->successResponse($payment->getTransaction(), 'Checkout Data');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->serverException($th);
        }
    }

    public function ordersList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'User ID field is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }

        $orders = Order::with(['items.product', 'orderShippingAddress', 'orderBillingAddress', 'payment', 'user', 'coupon'])
            ->where('user_id', $request->input('user_id'))->get();
        return $this->successResponse($orders, 'Orders fetched successfully');
    }

    public function getSingleOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ], [
            'order_id.required' => 'Order ID field is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }


        $order = Order::with(['items.product', 'orderShippingAddress', 'orderBillingAddress', 'payment', 'user', 'coupon'])
            ->where('id', $request->input('order_id'))
            ->first();
        return $this->successResponse($order, 'Order fetched successfully');
    }

    public function inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required',
        ], [
            'order_number.required' => 'Order Number field is required.',
        ]);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors()->toArray());
        }


        $order = Order::with([
            'items.product',
            'orderShippingAddress',
            'orderBillingAddress',
            'payment',
            'user',
            'coupon',
            'orderStatus'
        ])
            ->where('order_num', str_replace("#", "", $request->input('order_number')))
            ->first();

        return $this->successResponse($order, 'Order fetched successfully');
    }
}
