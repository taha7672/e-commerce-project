<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Invoice</title>
        <!-- STYLES -->
        <!-- LOCAL -->
        <style>
            body {
                font-family: Verdana, Arial, sans-serif;
            }

            td {
                vertical-align: top;
            }

            h1, h2, h3, h4, h5, h6 {
                margin: 0;
            }

            .page-break { page-break-after: always; }

            .mt-3 { margin-top: 3rem; }

            .mb-half { margin-bottom: 0.5rem; }
            .mb-2px { margin-bottom: 2px; }

            .ms-3 { margin-left: 3rem; }

            .w-full { width: 100%; }
            .w-half { width: 50%; }
            .w-60 { width: 60%; }

            .border-0 { border: 0 !important; }

            .center { text-align: center; }
            .title {
                font-weight: 500;
                font-size: 1rem;
                margin-bottom: 1rem;
            }

            .strip-table, .strip-table th, .strip-table td {
                border-collapse: collapse;
            }

            .strip-table td {
                padding: 0.4rem 0.5rem;
            }

            .strip-table thead {
                background: black;
                color: white;
            }

            .strip-table tbody td {
                border-bottom: 1px solid gray;
            }
            
        </style>
    </head>
    <body>
        <table class="mt-3 w-full">
            <tr>
                <td class="w-60 ">
                    <img class="ms-3" src="{{ $company['img'] }}" alt="logo" style="width: 320px;">
                </td>
                <td class="">
                    <h4 class="mb-2px">{{ $company['name'] }}</h4>
                    <div class="mb-2px">{{ $company['address'] }}</div>
                    <div class="mb-2px">{{ $company['state'] }}, {{ $company['country'] }} {{ $company['postal_code'] }}</div>
                    <div class="mb-2px">{{ $company['email'] }}</div>
                    <div class="mb-2px">{{ $company['phone'] }}</div>
                    <div class="mb-2px">{{ $company['site'] }}</div>
                </td>
            </tr>
        </table>

        <table class="mt-3 w-full">
            <tr>
                <td><h2 class="">Invoice</h2></td>
            </tr>

            <tr>
                <td class="w-60 ">
                    <table class="w-full">        
                        <tr>
                            <td class="w-half">
                                @if($order->orderBillingAddress)
                                    <div class="mb-2px">{{ isset($order->orderBillingAddress) ? $order->orderBillingAddress->first_name .' '. $order->orderShippingAddress->last_name : 'N.A.' }}</div>

                                    @if($order->orderBillingAddress->email)
                                        <div class="mb-2px">{{ $order->orderBillingAddress->email }}</div>
                                    @endif

                                    <div class="mb-2px">
                                        {{ $order->orderBillingAddress->address_line1 ?? '' }} 
                                        {{ $order->orderBillingAddress->address_line2 ?? '' }}
                                    </div>
                                    
                                    <div class="mb-2px">
                                        {{ $order->orderBillingAddress->city ?? '' }} 
                                        {{ $order->orderBillingAddress->state ?? '' }}
                                    </div>
                                    
                                    <div class="mb-2px">
                                        {{ $order->orderBillingAddress->country ?? '' }} 
                                        {{ $order->orderBillingAddress->postal_code ?? '' }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                <h4>Ship to:</h4>
                                @if($order->orderShippingAddress)
                                    <div class="mb-2px">{{ isset($order->orderShippingAddress) ? $order->orderShippingAddress->first_name .' '. $order->orderShippingAddress->last_name : 'N.A.' }}</div>

                                    @if($order->orderShippingAddress->email)
                                        <div class="mb-2px">{{ $order->orderShippingAddress->email }}</div>
                                    @endif

                                    <div class="mb-2px">
                                        {{ $order->orderShippingAddress->address_line1 ?? '' }}
                                        {{ $order->orderShippingAddress->address_line2 ?? '' }}
                                    </div>

                                    <div class="mb-2px">
                                        {{ $order->orderShippingAddress->city ?? '' }} 
                                        {{ $order->orderShippingAddress->state ?? '' }}
                                    </div>
                                    
                                    <div class="mb-2px">
                                        {{ $order->orderShippingAddress->country ?? '' }} 
                                        {{ $order->orderShippingAddress->postal_code ?? '' }}
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>

                <td class="">
                    <div class="mb-2px">Order Number: {{ $order->order_num }}</div>
                    <div class="mb-2px">Order Date: {{ $order->created_at->format('d.m.Y H:i') }}</div>
                    <div class="mb-2px">Payment Method: {{ $order->payment->payment_method ?? 'N.A.' }}</div>
                </td>
            </tr>
        </table>

        <table class="mt-3 w-full strip-table">
            <thead>
                <tr>
                    <td>Product</td>
                    <td>Quantity</td>
                    <td>Price</td>
                </tr>
            </thead>

            <tbody>
                <?php $subtotal = 0; ?>

                @if(isset($order->items) && count($order->items) > 0)
                    @foreach($order->items as $item)
                        <?php $subtotal = $subtotal +  $item->sub_total; ?>

                        <tr>
                            <td class="w-60">{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>@currency($item->sub_total, $order->currency_id)</td>
                        </tr>
                    @endforeach
                @endif

                <tr>
                    <td class="border-0"></td>
                    <td>Subtotal </td>
                    <td>@currency($order->total_amount, $order->currency_id)</td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td>VAT  </td>
                    <td>@currency($order->vat_amount, $order->currency_id)</td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td>Promotional Discount </td>
                    <td>@currency($order->discount_amount, $order->currency_id)</td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td>Shipping Amount </td>
                    <td>@currency($order->shipping_amount, $order->currency_id)</td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td>Grand Total </td>
                    <td>@currency($order->paid_amount, $order->currency_id)</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>