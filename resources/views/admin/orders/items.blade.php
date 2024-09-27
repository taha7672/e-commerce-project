      <div class='row mt-4'>
                        <div class="col-12-md">
                            <div class="table-responsive">
                                <table class="table" style="width:100%">
                                    <thead class="">
                                        <tr>
                                            <th scope="col">{{ __('tables.serial_no') }}</th>
                                            <th scope="col">{{ __('tables.items') }}</th>
                                            <th></th>
                                            <th class="text-end" scope="col">{{ __('tables.qty') }}</th>
                                            <th class="text-end" scope="col">{{ __('tables.price') }}</th>
                                            <th class="text-end" scope="col">{{ __('tables.total_amount') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
										@php
										   $subtotal = $discount = 0;
										@endphp
                                            @if(isset($order->items) && count($order->items) > 0)
                                                @foreach ($order->items as $index => $item)
                                                    @php
                                                       $subtotal = $subtotal +  $item->sub_total;
                                                    @endphp
                                                    <td>{{ $index + 1 }}</td>
                                                    <td class='text-capitalize'>{{$item->product->name}}</td>
                                                    <td>
                                                        @php
                                                            $imaePath = isset($items->product->image) ? $items->product->image: '';
                                                            $imagePath = public_path($imaePath);
                                                        @endphp

                                                        @if(isset($items->product->image) && file_exists($imagePath))
                                                            <img
                                                                src="{{ asset($items->product->image) }}"
                                                                alt="{{$items->product->name }}"
                                                                width="100"
                                                                onerror="this.onerror=null;this.src='{{ asset('images/dummy-image-portrait.jpg') }}';"
                                                            >
                                                        @else
                                                            <img
                                                                src="{{ asset('assets/images/dummy-image-portrait.jpg') }}"
                                                                alt="{{ isset($items->product->image) ? $items->product->image:''}}"
                                                                width="100"
                                                            >
                                                        @endif
                                                        </td>
                                                    <td class="text-end">{{$item->quantity}}</td>
                                                    <td class="text-end">@currency($item->price_at_order, $order->currency_id)</td>
                                                    <td class="text-end">@currency($item->sub_total, $order->currency_id)</td>
                                                @endforeach
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class='row row px-3 text-end'>
                        <div class="col-md-6 col-md-6 offset-md-6 px-3">
                            <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Subtotal</b> :</div>
                                <div class='col-md-4'>@currency($order->total_amount, $order->currency_id)</div>
                            </div>
                            <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>VAT</b> :</div>
                                <div class='col-md-4'>@currency($order->vat_amount, $order->currency_id)</div>
                            </div>
                            <!-- <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>VAT Include</b> :</div>
                                <div class='col-md-4'>0</div>
                            </div> -->
                             <!-- <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Pormotional Discount Include Amount</b> :</div>
                                <div class='col-md-4'>0</div>
                            </div> -->
                            @if( !empty($order->coupon->coupon_code))

                             @php
                                if($order->coupon->discount_type =='fixed'):
                                    $discount  =  $order->coupon->discount_amount;
                                else:
                                    if($order->coupon->discount_percentage > 0){
                                        $percentage = $order->coupon->discount_percentage;
                                        $discount = ($percentage / 100) * $subtotal;
                                    }
                                endif;
                            @endphp
                            <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Coupon Applied</b> :</div>
                                <div class='col-md-4'>
                                    {{$order->coupon->coupon_code ?? ''}}
                                    {{$order->coupon->discount_amount ?? '0'}}{{(!empty($order->coupon->discount_type) && $order->coupon->discount_type == 'fixed') ? 'Flat' : '%'}}
                                </div>
                            </div>
                            <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Pormotional Discount</b> :</div>
                                <div class='col-md-4'>  @currency($order->discount_amount, $order->currency_id)</div>
                            </div>
                            <!--
                             <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Pormotional Discount Include Amount</b> :</div>
                                <div class='col-md-4'>0</div>
                            </div>-->
                            @else
                                <div class="row pb-3">
                                    <div class='col-md-7 offset-md-1'><b>Promotional Discount</b> :</div>
                                    <div class='col-md-4'>@currency(0.00, $order->currency_id)</div>
                                </div>
                            @endif

                            <div class="row pb-3">
                                <div class='col-md-7 offset-md-1'><b>Shipping Amount</b> :</div>
                                <div class='col-md-4'>@currency($order->shipping_amount, $order->currency_id)</div>
                            </div>

                            <div class="row">
                                <div class='col-md-7 offset-md-1'><b>Grand Total</b> :</div>
                                <div class='col-md-4'>
                                    @php
                                    if( !empty($discount >0)):
                                        if($subtotal > $discount){
                                                $subtotal = $subtotal -$discount;
                                        }else{
                                             $subtotal = $discount - $subtotal;
                                        }
                                    endif;
                                    @endphp
                                    @currency($order->paid_amount, $order->currency_id)
                                </div>
                            </div>
                        </div>
                    </div>
               