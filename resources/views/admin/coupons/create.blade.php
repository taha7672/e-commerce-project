@extends('layouts.admin')

@section('breadcrumb',  __('pages.coupons'))
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <style>
        .select2-container--default .select2-selection--multiple{
            border: 1px solid #bfc9d4;
            height: 48px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice{
            height: 35px;
            line-height: 35px;
            font-size: 17px;
        }
        .select2-container--default .select2-search--inline .select2-search__field{
            height: 30px;
            line-height: 30px;
            font-size: 17px;
            margin: 8px 5px;
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
                            <h4>{{__('pages.add_new_coupon')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{route('admin.coupons.index')}}" class="btn btn-info">{{__('pages.go_back')}}</a>
                        </div>
                    </div>
                    {{-- errors --}}
                    @include('partials.errors')
                    <form method="POST" action="{{route('admin.coupons.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title">{{__('pages.coupon_code')}}<span class="text-danger">*</span> </label>
                                    <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="{{__('pages.coupon_code')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="thumbnail">{{__('pages.discount_type')}} </label>
                                    <select name="discount_type" id="discount_type" class="form-control">
                                        <option value="percentage">{{__('pages.percentage')}}</option>
                                        <option value="fixed">{{__('pages.fixed')}}</option>
                                    </select>
                                    <!-- <input type="text" name="discount_type" id="discount_type" class="form-control" placeholder="Discount Type" required> -->
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="stockQuantity">{{__('pages.discount_percentage')}}<span class="text-danger">*</span> </label>
                                    <input name="discount_percentage" id="discount_percentage" class="form-control" placeholder="3" required />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="discount_amount">{{__('pages.discount_amount')}} <span class="text-danger">*</span> </label>
                                    <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="4500" required />
                                </div>
                            </div>
                          
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="expiry_date">{{__('pages.expiry_date')}}<span class="text-danger">*</span> </label>
                                    <input type='date' name="expiry_date" id="expiry_date" class="form-control" placeholder="4500" required />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="minimum_order_amount">{{__('pages.minimum_order_amount')}} <span class="text-danger">*</span> </label>
                                    <input type="number" name="minimum_order_amount" id="minimum_order_amount" class="form-control" placeholder="4500" required />
                                </div>
                            </div>
                           
                            <div class="col-6">
                                <label for="published">{{__('pages.one_time_use')}}</label>
                                <select name="one_time_use" id="one_time_use" class="form-control">
                                    <option value="1">{{__('pages.yes')}}</option>
                                    <option value="0">{{__('pages.no')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">
                                {{__('pages.submit')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@php
    $uploadUrl = route("admin.upload-editor-media").'?_token='.csrf_token();
@endphp
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.2/super-build/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endpush
