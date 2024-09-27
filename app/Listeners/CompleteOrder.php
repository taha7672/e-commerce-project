<?php
namespace App\Listeners;
 
use App\Models\Order;
use Laravel\Paddle\Cashier;
use Laravel\Paddle\Events\TransactionCompleted; 
use App\Models\OrderBillingAddress;
use App\Models\OrderShippingAddress;
use App\Models\EmailTemplate; 
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
 
class CompleteOrder
{
    /**
     * Handle the incoming Cashier webhook event.
     */
    public function handle(TransactionCompleted $event): void
    {
        $orderId = $event->payload['data']['custom_data']['order_id'] ?? null;
        $input = $event->payload['data']['custom_data']['input'] ?? [];
        $payment_method = $event->payload['data']['custom_data']['payment_method'] ?? 'Paddle';
 
        $order = Order::findOrFail($orderId);
 
        // Add Payment Data
        $payment_data = array(
            'order_id' => $order->id,
            'total_amount' => $order->paid_amount,
            'payment_method' => $payment_method,
            'payment_date' => date('Y-m-d H:i:s'),
            'payment_status' => 'success',
            'transaction_id' => '' 
        );
        Payment::create($payment_data);
        
        /* 
            $order->status = 'processing';
            $order->save();
            
            // Add Order Status
            OrderStatus::create([
                'order_id' => $order->id,
                'status' => 'processing',
                'status_date' => date('Y-m-d H:i:s'),
                'location' => ''
            ]); 
        */
        
        // Add Order Addresses
        $billing_data = $input['billing_address'];
        $billing_data['order_id'] = $order->id;
        OrderBillingAddress::create($billing_data);
        
        $shipping_data = $input['shipping_address'];
        $shipping_data['order_id'] = $order->id;
        OrderShippingAddress::create($shipping_data); 
        
        // Fetch the template
        $template = EmailTemplate::where('name', 'New Order Notification')->first();
        // dd($template);
        if ($template) { 
            try{
                Mail::to($order->user->email)->send(new \App\Mail\CustomEmail($template, $order)); 
            }
            catch(\Exception $ex){
                
            }
        }
        
        
    }
}