<?php

use App\Models\Order;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('order:generate_order_num', function () {
    $orders = Order::limit(100)->cursor();
    foreach ($orders as $order) {    
        if ($order->order_num == null) {
            $order->order_num = uniqueOrderNumber($order);
            $order->save();
        }
    }

    $this->line("Order number has been generated for 100 orders");
})->purpose('Generate order number for first 100 orders if not exists');