<tbody>
    <tr>
        <td><b>Name and surname</b> <span class='float-end'>:</span></td>
        <td class='text-capitalize'>{{ isset($order->orderShippingAddress) ? $order->orderShippingAddress->first_name .' '. $order->orderShippingAddress->last_name : 'NA'}}</td>
    </tr>
    <tr>
        <td><b>E-mail</b> <span class='float-end'>:</span></td>
        <td >{{$order->orderShippingAddress->email ?? 'NA'}}</td>
    </tr>
    <tr>
        <td><b>Delivery address</b> <span class='float-end'> :</span> </td>
        <td>
            @if(isset($order->orderShippingAddress->postal_code))
                Postal code <span>:</span> {{$order->orderShippingAddress->postal_code}}
                </br>
            @endif
            @if(isset($order->orderShippingAddress->address_line1))
                Address <span>:</span> {{$order->orderShippingAddress->address_line1}}


            @endif
            @if(isset($order->orderShippingAddress->address_line2))
                    {{$order->orderShippingAddress->address_line2}}
                </br>

            @endif
            @if(isset($order->orderShippingAddress->city))
                    {{$order->orderShippingAddress->city}}/
            @endif
            @if(isset($order->orderShippingAddress->state))
                    {{$order->orderShippingAddress->state}}/
            @endif
            @if(isset($order->orderShippingAddress->country))
                    {{$order->orderShippingAddress->country}}
            @endif
        </td>
    </tr>
    <tr>
        <td><b>Mobile Phone 1</b> <span class='float-end'>:</span></td>
        <td >{{$order->orderShippingAddress->phone ?? 'NA'}}</td>
    </tr>
</tbody>