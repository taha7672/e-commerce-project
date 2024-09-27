<?php use Illuminate\Support\Js; ?>

@extends('layouts.admin')

@section('breadcrumb', 'Orders')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #bfc9d4;
            height: 48px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            height: 35px;
            line-height: 35px;
            font-size: 17px;
        }
        .select2-container--default .select2-search--inline .select2-search__field {
            height: 30px;
            line-height: 30px;
            font-size: 17px;
            margin: 8px 5px;
        }

        .modal-content {
            background: var(--bs-body-bg) !important;
        }

        label.error {
            color: red;
        }
    </style>
@endpush

@section('content')
    @php
      $subtotal = 0;
      $discount = 0;
      $loaderHtml = '<div class="spinner-border text-primary align-self-center loader-sm mx-auto"></div>';
    @endphp
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-6">
                            <h4>
                                Order No: #{{$order->order_num}}
                                <br>
                                <span class="badge badge-light-info mb-2 me-4">{{ $order->status }}</span>
                            </h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.order.generate-invoice', $order->id) }}" class="btn btn-success">Generate Invoice</a>
                            <a href="javascript:void(0);" onclick="underProcess()" class="btn btn-warning">Send Invoice</a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-info">Go Back</a>
                        </div>
                    </div>

                    {{-- errors --}}
                    @include('partials.errors')

                    <div class='row'>
                        <div class="col-12-md">
                            @if($order && $order->payment && $order->payment->payment_date)
                            <span class="badge badge-light-dark mb-2 me-2">Paid On: {{ date('d.m.Y, H:i', strtotime($order->payment->payment_date)) }}</span>
                            @endif

                            @if($order && $order->created_at)
                            <span class="badge badge-light-dark mb-2 me-2">Placed On: {{ date('d.m.Y, H:i', strtotime($order->created_at)) }}</span>
                            @endif

                            @if($order && $order->updated_at)
                            <span class="badge badge-light-dark mb-2 me-2">Updated: {{ date('d.m.Y, H:i', strtotime($order->updated_at)) }}</span>
                            @endif
                        </div>
                    </div>
					@include('admin.orders.items')
               </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-sm-8 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <!---->
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>Shipping information</h4>
                        </div>
                        <div class="col-4">
                            <a data-bs-toggle="modal" data-bs-target="#shippingAddrModal" class="float-end btn btn-info btn-icon _effect--ripple waves-effect waves-light" href="javascript:void(0);">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-12-md">
                            <div class="inv--product-table-section">
                                <div class="table-responsive">
                                    <table class="table" id="shippingTable">
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
                                                <td><b>Mobile Phone</b> <span class='float-end'>:</span></td>
                                               <td >{{$order->orderShippingAddress->phone ?? 'NA'}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---->
                </div>

                <div class="widget-content widget-content-area br-8 mt-4">
                    <!---->
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>Billing information</h4>
                        </div>
                        <div class="col-4">
                            <a data-bs-toggle="modal" data-bs-target="#billingAddrModal" class="float-end btn btn-info btn-icon _effect--ripple waves-effect waves-light" href="javascript:void(0);">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-12-md">
                            <div class="inv--product-table-section">
                                <div class="table-responsive">
                                    <table class="table" id="billingTable">
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
                                                <td><b>Mobile Phone</b> <span class='float-end'>:</span></td>
                                                <td >{{$order->orderBillingAddress->phone ?? 'NA'}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---->
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-sm-4 layout-spacing">
                @if($order->payment)
                <div class="widget-content widget-content-area br-8 mb-4">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4>Payment</h4>
                            <div class='row'>
                                <div class="col-12-md">
                                    <div class="d-inline">
                                        <div class="row">
                                            <div class="col-6">Method</div>
                                            <div class="col-6" class="text-capitalize">{{ $order->payment->payment_method }}</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">Payment Date</div>
                                            <div class="col-6">{{ $order->payment->payment_date ? date('d.m.Y', strtotime($order->payment->payment_date)) : 'N/A' }}</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">Transaction Id</div>
                                            <div class="col-6">{{ $order->payment->transaction_id }}</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">Total Amount</div>
                                            <div class="col-6">@currency($order->payment->total_amount, $order->currency_id)</div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">Status</div>
                                            <div class="col-6">{{ $order->payment->payment_status }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="widget-content widget-content-area br-8">
                      <div class="row mb-3">
                        <div class="col-12">
                            <h4>Order Status</h4>
                            <div class='row'>
                                <div class="col-12-md">
                                    <div class="d-inline">
                                        @csrf
                                        <select name='status' class="form-select custom-select update-satus" data-id="{{ $order->id }}">
                                            <option value='pending' @if( $order->status == 'pending') selected @endif>Pending</option>
                                            <option value='processing' @if( $order->status == 'processing') selected @endif>Processing</option>
                                            <option value='shipped' @if( $order->status == 'shipped') selected @endif>Shipped</option>
                                            <option value='delivered' @if( $order->status == 'delivered') selected @endif>Delivered</option>
                                        <option value='cancelled' @if( $order->status == 'cancelled') selected @endif>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area br-8 mt-4">
                      <div class="row">
                        <div class="col-12">
                            <h4>Order note</h4>
                            <div class='row'>
                                <div class="col-12-md">
                                    <form id='addNote' class="d-inline">
                                        @csrf
                                        <textarea name='note' class="form-control">{{ $order->note}}</textarea>
                                        <button type='submit' class=' btn btn-info mt-3 float-end'>Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="widget-content widget-content-area br-8 mt-4">
                    <div class="row">
                        <div class="col-12">
                            <h4>Status History</h4>

                            <div class='row'>
                                <div class="col-12-md">
                                    <?php $history = $order->orderStatus; ?>
                                    @foreach($history as $s)
                                        <div class="mb-2">
                                            Status is changed to <b>{{ $s->status }}</b> on {{ $s->created_at->format('d.m.Y H:i') }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @if($order->orderShippingAddress)
    <div class="modal fade" id="shippingAddrModal" tabindex="-1" role="dialog" aria-labelledby="shippingAddr" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shipping Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form id="shippingAddrForm" class="row gap-4">
                        <input type="hidden" name="id" value="{{ $order->orderShippingAddress->id }}">

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">First Name</label>
                                        <input name="first_name" type="text" class="form-control" id="" value="{{ $order->orderShippingAddress->first_name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input name="last_name" type="text" class="form-control" id="" value="{{ $order->orderShippingAddress->last_name ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input name="email" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->email ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input name="phone" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->phone ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Province</label>
                                        <!-- <input name="state" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->state ?? ''}}"> -->
                                        <select name="state" data-name="state" class="form-select">
                                            <option value="">Select Province</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" @if($province->name == $order->orderShippingAddress->state) selected @endif>{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">District</label>
                                        <!-- <input name="city" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->city ?? ''}}"> -->
                                        <select name="city" data-name="city" class="form-select" @if($shippingDistricts->count() == 0) disabled @endif>
                                            @foreach($shippingDistricts as $dist)
                                            <option value="{{ $dist->id }}" @if($dist->district_name == $order->orderShippingAddress->city) selected @endif>{{ $dist->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Village</label>
                                <!-- <input name="address_line1" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->address_line1 ?? ''}}"> -->
                                <select name="village" data-name="village" class="form-select" @if($shippingVillages->count() == 0) disabled @endif>
                                    @foreach($shippingVillages as $vil)
                                    <option value="{{ $vil->id }}" @if($vil->village_name == $order->orderShippingAddress->address_line2) selected @endif>{{ $vil->village_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Address Line</label>
                                <input name="address_line1" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->address_line1 ?? ''}}">
                            </div>
                        </div>

                        <!-- <div class="col-12">
                            <div class="form-group">
                                <label for="">Address Line 2</label>
                                <input name="address_line2" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->address_line2 ?? ''}}">
                            </div>
                        </div> -->
                        
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Postal Code</label>
                                        <input name="postal_code" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->postal_code ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <input type="text" class="form-control" id="" disabled value="{{$order->orderShippingAddress->country ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="button" id="saveShippingAddr" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($order->orderBillingAddress)
    <div class="modal fade" id="billingAddrModal" tabindex="-1" role="dialog" aria-labelledby="billingAddr" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Billing Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <form id="billingAddrForm" class="row gap-4">
                        <input type="hidden" name="id" value="{{ $order->orderBillingAddress->id }}">

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">First Name</label>
                                        <input name="first_name" type="text" class="form-control" id="" value="{{ $order->orderBillingAddress->first_name ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input name="last_name" type="text" class="form-control" id="" value="{{ $order->orderBillingAddress->last_name ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input name="email" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->email ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input name="phone" type="text" class="form-control" id="" value="{{$order->orderShippingAddress->phone ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Province</label>
                                        <!-- <input name="state" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->state ?? ''}}"> -->
                                        <select name="state" data-name="state" class="form-select">
                                            <option value="">Select Province</option>
                                            @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" @if($province->name == $order->orderBillingAddress->state) selected @endif>{{ $province->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">District</label>
                                        <!-- <input name="city" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->city ?? ''}}"> -->
                                        <select name="city" data-name="city" class="form-select" @if($billingDistricts->count() == 0) disabled @endif>
                                            @foreach($billingDistricts as $dist)
                                            <option value="{{ $dist->id }}" @if($dist->district_name == $order->orderBillingAddress->city) selected @endif>{{ $dist->district_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Village</label>
                                <!-- <input name="address_line1" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->address_line1 ?? ''}}"> -->
                                <select name="village" data-name="village" class="form-select" @if($billingVillages->count() == 0) disabled @endif>
                                    @foreach($billingVillages as $vil)
                                    <option value="{{ $vil->id }}" @if($vil->village_name == $order->orderBillingAddress->address_line2) selected @endif>{{ $vil->village_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Address Line</label>
                                <input name="address_line1" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->address_line1 ?? ''}}">
                            </div>
                        </div>

                        <!-- <div class="col-12">
                            <div class="form-group">
                                <label for="">Address Line 2</label>
                                <input name="address_line2" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->address_line2 ?? ''}}">
                            </div>
                        </div> -->
                        
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Postal Code</label>
                                        <input name="postal_code" type="text" class="form-control" id="" value="{{$order->orderBillingAddress->postal_code ?? ''}}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <input type="text" class="form-control" id="" disabled value="{{$order->orderBillingAddress->country ?? ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn btn-light-dark" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="button" id="saveBillingAddr" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function () {
            $('#addNote').on('submit', function (e) {
                let ele = this;
                e.preventDefault();
                let fd = new FormData(ele);

                let btn = ele.querySelector('[type="submit"]');

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.order.note.ajax', $order->id) }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        btn.setAttribute('disabled', true);
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving';
                    },
                    success: function (res) {
                        if (res.status_code == 422) {
                            popError(res.message);
                            return;
                        }

                        Swal.fire({
                            title: 'Success !!!',
                            text: "Note is saved successfully",
                            icon: 'success',
                            timer: 3000,
                        });
                    },
                    error: function(xhr) {
                        let msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : "Something went wrong";

                        popError(msg);
                    }, 
                    complete: function() {
                        btn.removeAttribute('disabled');
                        btn.innerHTML = 'Save';
                    },
                });
            })

            $(document).on('change','.update-satus',function(){
                let ele = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to update status!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, update it!'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({
                        method: "POST",
                        url: "{{ route('admin.order.status.ajax') }}",
                        data: {
                            id: ele.dataset.id,
                            status: ele.value
                        },
                        success: function (res) {
                            if (res.status_code == 422) {
                                popError(res.message);
                                return;
                            }

                            Swal.fire({
                                title: 'Success !!!',
                                text: "Status is updated successfully",
                                icon: 'success',
                                timer: 3000,
                            });
                        },
                        error: function(xhr) {
                            let msg = (xhr.responseJSON && xhr.responseJSON.error)
                                ? xhr.responseJSON.error
                                : "Something went wrong";

                            popError(msg);
                        }
                    });
                })
            });

            $('#shippingAddrModal').on('change', '[data-name="state"]', function () {
                let ele = this;

                $('#shippingAddrModal [data-name="village"]').html('').prop('disabled', true);
                $('#shippingAddrModal [data-name="city"]').html('').prop('disabled', true);
                if (ele.value == "") {
                    return;
                }

                $.ajax({
                    "url": `/api/v1/get-districts/${ele.value}`,
                    "method": "POST",
                    success: function (res) {
                        if (res.status_code != 200) {
                            popError();
                            return;
                        }

                        let options = res.data.reduce(function(acc, cur) {
                            return acc + `<option value="${cur.id}">${cur.district_name}</option>`;
                        }, `<option value="">Select District</option>`);

                        $('#shippingAddrModal [data-name="city"]').html(options).removeAttr('disabled');
                        
                    },
                    error: function() {
                        popError();
                    }
                });
            });

            $('#shippingAddrModal').on('change', '[data-name="city"]', function () {
                let ele = this;

                $('#shippingAddrModal [data-name="village"]').html('').prop('disabled', true);
                if (ele.value == "") {
                    return;
                }

                $.ajax({
                    "url": `/api/v1/get-villages/${ele.value}`,
                    "method": "POST",
                    success: function (res) {
                        if (res.status_code != 200) {
                            popError();
                            return;
                        }

                        let options = res.data.reduce(function(acc, cur) {
                            return acc + `<option value="${cur.id}">${cur.village_name}</option>`;
                        }, `<option value="">Select Village</option>`);

                        $('#shippingAddrModal [data-name="village"]').html(options).removeAttr('disabled');
                    },
                    error: function() {
                        popError();
                    }
                });
            });

            $("#shippingAddrForm").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    state: "required",
                    city: "required",
                    address_line1: "required",
                    // address_line2: "required",
                    postal_code: "required",
                }
            });

            $(document).on('click', '#saveShippingAddr', function () {
                if ( !$("#shippingAddrForm").valid() ) {
                    return;
                }

                let form = document.querySelector('#shippingAddrForm');
                let fd = new FormData(form);
                let btn = document.querySelector('#saveShippingAddr');

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.order.shipping-address.save.ajax') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        btn.setAttribute('disabled', true);
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving';
                    },
                    success: function (res) {
                        if (res.status_code == 422) {
                            popError(res.message);
                            return;
                        }

                        Swal.fire({
                            title: 'Success !!!',
                            text: "Shipping Address is saved successfully",
                            icon: 'success',
                            timer: 3000,
                        });

                        $('#shippingAddrModal').modal('hide');
                        refreshShippingTable();
                    },
                    error: function(xhr) {
                        let msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : "Something went wrong";

                        popError(msg);
                    }, 
                    complete: function() {
                        btn.removeAttribute('disabled');
                        btn.innerHTML = 'Save';
                    },
                });
            })

            // Billing
            $('#billingAddrModal').on('change', '[data-name="state"]', function () {
                let ele = this;

                $('#billingAddrModal [data-name="village"]').html('').prop('disabled', true);
                $('#billingAddrModal [data-name="city"]').html('').prop('disabled', true);
                if (ele.value == "") {
                    return;
                }

                $.ajax({
                    "url": `/api/v1/get-districts/${ele.value}`,
                    "method": "POST",
                    success: function (res) {
                        if (res.status_code != 200) {
                            popError();
                            return;
                        }

                        let options = res.data.reduce(function(acc, cur) {
                            return acc + `<option value="${cur.id}">${cur.district_name}</option>`;
                        }, `<option value="">Select District</option>`);

                        $('#billingAddrModal [data-name="city"]').html(options).removeAttr('disabled');
                        
                    },
                    error: function() {
                        popError();
                    }
                });
            });

            $('#billingAddrModal').on('change', '[data-name="city"]', function () {
                let ele = this;

                $('#billingAddrModal [data-name="village"]').html('').prop('disabled', true);
                if (ele.value == "") {
                    return;
                }

                $.ajax({
                    "url": `/api/v1/get-villages/${ele.value}`,
                    "method": "POST",
                    success: function (res) {
                        if (res.status_code != 200) {
                            popError();
                            return;
                        }

                        let options = res.data.reduce(function(acc, cur) {
                            return acc + `<option value="${cur.id}">${cur.village_name}</option>`;
                        }, `<option value="">Select Village</option>`);

                        $('#billingAddrModal [data-name="village"]').html(options).removeAttr('disabled');
                    },
                    error: function() {
                        popError();
                    }
                });
            });

            $("#billingAddrForm").validate({
                rules: {
                    first_name: "required",
                    last_name: "required",
                    phone: {
                        required: true,
                        minlength: 10,
                        maxlength: 10,
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    state: "required",
                    city: "required",
                    address_line1: "required",
                    // address_line2: "required",
                    postal_code: "required",
                }
            });

            $(document).on('click', '#saveBillingAddr', function () {
                if ( !$("#billingAddrForm").valid() ) {
                    return;
                }

                let form = document.querySelector('#billingAddrForm');
                let fd = new FormData(form);
                let btn = document.querySelector('#saveBillingAddr');

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.order.billing-address.save.ajax') }}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        btn.setAttribute('disabled', true);
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving';
                    },
                    success: function (res) {
                        if (res.status_code == 422) {
                            popError(res.message);
                            return;
                        }

                        Swal.fire({
                            title: 'Success !!!',
                            text: "Billing Address is saved successfully",
                            icon: 'success',
                            timer: 3000,
                        });

                        $('#billingAddrModal').modal('hide');
                        refreshBillingTable();
                    },
                    error: function(xhr) {
                        let msg = (xhr.responseJSON && xhr.responseJSON.error)
                            ? xhr.responseJSON.error
                            : "Something went wrong";

                        popError(msg);
                    }, 
                    complete: function() {
                        btn.removeAttribute('disabled');
                        btn.innerHTML = 'Save';
                    },
                });
            })
        });

        function popError(msg = "Something went wrong") {
            Swal.fire({
                title: 'Error !!!',
                text: msg,
                icon: 'error',
                timer: 3000,
            });
        }

        function refreshShippingTable() {
            let jqEle = $('#shippingTable');

            $.ajax({
                method: "POST",
                url: "{{ route('admin.order.fetch.shipping-table', $order->id) }}",
                beforeSend: function () {
                    jqEle.html('<div class="position-relative w-100" style="height: 2rem;"><div class="align-self-center loader-sm mx-auto position-absolute spinner-border text-primary" style="left: 50%;"></div></div>');
                },
                success: function (res) {
                    if (res.status_code == 422) {
                        popError(res.message);
                        return;
                    }

                    jqEle.html(res);
                },
                error: function(xhr) {
                    let msg = (xhr.responseJSON && xhr.responseJSON.error)
                        ? xhr.responseJSON.error
                        : "Something went wrong";

                    popError(msg);
                },
            });
        }

        function refreshBillingTable() {
            let jqEle = $('#billingTable');

            $.ajax({
                method: "POST",
                url: "{{ route('admin.order.fetch.billing-table', $order->id) }}",
                beforeSend: function () {
                    jqEle.html('<div class="position-relative w-100" style="height: 2rem;"><div class="align-self-center loader-sm mx-auto position-absolute spinner-border text-primary" style="left: 50%;"></div></div>');
                },
                success: function (res) {
                    if (res.status_code == 422) {
                        popError(res.message);
                        return;
                    }

                    jqEle.html(res);
                },
                error: function(xhr) {
                    let msg = (xhr.responseJSON && xhr.responseJSON.error)
                        ? xhr.responseJSON.error
                        : "Something went wrong";

                    popError(msg);
                },
            });
        }

        function underProcess() {
            Swal.fire({
                title: 'Info !!!',
                text: "This functionality is under process",
                icon: 'info',
                timer: 3000,
            });
        }

    </script>

@endpush
