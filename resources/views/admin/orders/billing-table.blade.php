<tbody>
    <tr>
        <td><b>Name and surname</b> <span class='float-end'>:</span></td>
        <td class='text-capitalize'>{{isset($order->orderBillingAddress) ? $order->orderBillingAddress->first_name .' '. $order->orderBillingAddress->last_name : 'NA'}}</td>
    </tr>
    <tr>
        <td><b>E-mail</b> <span class='float-end'>:</span></td>
        <td >{{$order->orderBillingAddress->email ?? 'NA'}}</td>
    </tr>
    <tr>
        <td><b>Delivery address</b> <span class='float-end'> :</span> </td>
        <td>
            @if(isset($order->orderBillingAddress->postal_code))
                Postal code <span>:</span> {{$order->orderBillingAddress->postal_code}}
                </br>
            @endif
            @if(isset($order->orderBillingAddress->address_line1))
                Address <span>:</span> {{$order->orderBillingAddress->address_line1}}


            @endif
            @if(isset($order->orderBillingAddress->address_line2))
                    {{$order->orderBillingAddress->address_line2}}
                </br>

            @endif
            @if(isset($order->orderBillingAddress->city))
                    {{$order->orderBillingAddress->city}}/
            @endif
            @if(isset($order->orderBillingAddress->state))
                    {{$order->orderBillingAddress->state}}/
            @endif
            @if(isset($order->orderBillingAddress->country))
                    {{$order->orderBillingAddress->country}}
            @endif
        </td>
    </tr>
    <tr>
        <td><b>Mobile Phone 1</b> <span class='float-end'>:</span></td>
        <td >{{$order->orderBillingAddress->phone ?? 'NA'}}</td>
    </tr>
</tbody>