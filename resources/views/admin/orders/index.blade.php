@extends('layouts.admin')

@section('breadcrumb',  __('pages.orders'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/src/table/datatable/datatables.css')}}">
    <style>
    .form-select.custom-select{
       max-width: 135px;
       min-width: 135px;
    }
    </style>
@endpush
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.orders')}}</h4>
                        </div>
                    </div>
                    <table id="tags" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
								<!--
                                <th>No.</th>
								-->
                                <th style="width:150px">{{ __('tables.order_no') }}</th>
                                <th>{{ __('tables.buyer') }}</th>
                                <th>{{ __('tables.quantity') }}</th>
                                <th>{{ __('tables.price') }}</th>
                                <th>{{ __('tables.shipping') }}</th>
                                <th>{{ __('tables.billing') }}</th>
                                <th>{{ __('tables.status') }}</th>
                                <th class="no-content">{{ __('tables.action') }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                            @if(count($orders)>0)

                                @foreach ($orders as $index => $order)

                                    <tr>
                                       <!-- <td>{{ $index + 1 }}</td>-->
                                        <td>{{isset($order->order_num) ? $order->order_num: 'N/A' }}</td>
                                        <td class="text-capitalize">{{isset($order->user->name) ? $order->user->name: 'N/A' }}</td>
                                        <td>{{isset($order->items) ? count($order->items) : 1}}</td>
                                        <td>@currency($order->total_amount, $order->currency_id, 2)</td>
                                        <td>
                                            @php
                                                $shippingAddresses = $order->user->billingAddresses ?? null;
                                                $shippingAddresses = $shippingAddresses[0] ?? null;
                                            @endphp
                                                    @if(isset($shippingAddresses->postal_code))
                                                        {{$shippingAddresses->postal_code}}
                                                        </br>
                                                    @endif
                                                    @if(isset($shippingAddresses->address_line1))
                                                        {{$shippingAddresses->address_line1}}
                                                        </br>
                                                    @endif
                                                    @if(isset($shippingAddresses->address_line2))
                                                         {{$shippingAddresses->address_line2}}
                                                        </br>
                                                    @endif
                                                    @if(isset($shippingAddresses->city))
                                                         {{$shippingAddresses->city}}/
                                                    @endif
                                                    @if(isset($shippingAddresses->state))
                                                         {{$shippingAddresses->state}}/
                                                    @endif
                                                    @if(isset($shippingAddresses->country))
                                                         {{$shippingAddresses->country}}
                                                    @endif</td>
                                        <td>@php
                                               $billingAddresses = $order->user->billingAddresses ?? null;
                                                $billingAddresses = $billingAddresses[0] ?? null;
                                            @endphp
                                                    @if(isset($billingAddresses->postal_code))
                                                       {{$billingAddresses->postal_code}}
                                                        </br>
                                                    @endif
                                                    @if(isset($billingAddresses->address_line1))
                                                       {{$billingAddresses->address_line1}}
                                                        </br>
                                                    @endif
                                                    @if(isset($billingAddresses->address_line2))
                                                         {{$billingAddresses->address_line2}}
                                                        </br>
                                                    @endif
                                                    @if(isset($billingAddresses->city))
                                                         {{$billingAddresses->city}}/
                                                    @endif
                                                    @if(isset($billingAddresses->state))
                                                         {{$billingAddresses->state}}/
                                                    @endif
                                                    @if(isset($billingAddresses->country))
                                                         {{$billingAddresses->country}}
                                                    @endif
                                        </td>
                                        <td>
                                            <div class="d-inline">
                                                <select name='status' data-id="{{ $order->id }}" class=" custom-select update-satus form-select ">
                                                    <option value='pending' @if( $order->status == 'pending') selected @endif>{{__('pages.pending')}}</option>
                                                    <option value='processing' @if( $order->status == 'processing') selected @endif>{{__('pages.processing')}}</option>
                                                    <option value='shipped' @if( $order->status == 'shipped') selected @endif>{{__('pages.shipped')}}</option>
                                                    <option value='delivered' @if( $order->status == 'delivered') selected @endif>{{__('pages.delivered')}}</option>
                                                    <option value='cancelled' @if( $order->status == 'cancelled') selected @endif>{{__('pages.cancelled')}}</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-icon" href="{{route('admin.orders.show',$order->id)}}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('admin-assets/plugins/src/table/datatable/datatables.js')}}"></script>
    <script>
        $(function() {
            $(document).on('change', '.update-satus', function() {
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
        });

        $('#tags').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10,
            "columnDefs": [
                {
                    targets: [0],
                    render: function ( data, type, row ) {
                        if ( type === 'sort' ) {
                            let a = data.split("-");
                            return Number(a[a.length-1]);
                        } else { 
                            return data;
                        }
                    }
                }
            ]
        });
        $(document).on('click','.delete-blog',function(){
            let delete_form=$(this).parent('form');
            Swal.fire({
                title: "{{ __('pages.are_you_sure') }}?",
                text: "{{ __('pages.you_want_to_delete_this') }}?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('pages.yes,_delete_it') }}",
                cancelButtonText: "{{ __('pages.cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                   delete_form[0].submit();
                }
            })
        });

        function popError(msg) {
            Swal.fire({
                title: 'Error !!!',
                text: msg,
                icon: 'error',
                timer: 3000,
            });
        }

    </script>
@endpush
