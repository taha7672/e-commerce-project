<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PageController extends Controller
{
    public function home(){
        return redirect('/admin');
    }

    public function updateEFT(Request $request){
        $threshold = env('EFT_ORDER_THRESHOLD','-8 hours' );
        $payment_types = ['EFT/Money Transfer option', 'Cash On Delivery'];
        $date_from = date('Y-m-d H:i:s', strtotime($threshold));
        $orders = Order::whereHas('payment', function($query) use ($date_from, $payment_types){
            $query->whereIn('payment_method', $payment_types)
            ->where('created_at' , '<', $date_from)
            ->where('payment_status' , 'Pending');
        })
        ->where('status', '!=', 'cancelled')
        ->get();
        foreach($orders as $order){
            $order->status ='cancelled';
            $order->save(); 
        }
    }
}
