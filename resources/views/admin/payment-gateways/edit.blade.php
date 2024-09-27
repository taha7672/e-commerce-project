@extends('layouts.admin')

@section('breadcrumb',  __('pages.payment_gateways'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/src/table/datatable/datatables.css')}}">
@endpush
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.update_payment_gateway')}}</h4>
                        </div> 
                    </div>
                             
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                   
                    <form method="POST" action="{{ route('admin.payment-gateways.update', $paymentGateway->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mt-4">
                            <label for="name">{{__('pages.gateway_name')}} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $paymentGateway->name }}" required>
                        </div>
                        <div class="form-group mt-4">
                            <label for="name">{{__('pages.Ggateway_type')}} <span class="text-danger">*</span></label>
                            <select name="type" id="type" required class="form-select">
                                <option value="">{{__('pages.select_gateway_type')}}</option>
                                @if(count($gateways_type) )
                                    @foreach($gateways_type as $gateway => $name)
                                        <option value="{{ $gateway }}" {{ $paymentGateway->type == $gateway ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mt-4 nocod">
                            <label for="credentials">{{__('pages.API_Key')}} <span class="text-danger">*</span></label>
                            <input name="credentials[api_key]" class="form-control" value="{{ $paymentGateway->credentials['api_key']??'' }}" />
                        </div>
                        <div class="form-group mt-4 nocod">
                            <label for="credentials">{{__('pages.API_Secret_Vendor_ID')}}</label>
                            <input name="credentials[api_secret]" class="form-control" value="{{ $paymentGateway->credentials['api_secret']??'' }}"  />
                        </div>
                        <div class="form-group mt-4 nocod">
                            <label for="credentials">{{__('pages.base_URL')}}</label>
                            <input name="credentials[base_url]" class="form-control" value="{{ $paymentGateway->credentials['base_url']??'' }}"  />
                        </div>
                        <div class="form-group mt-4 d-none paddle nocod">
                            <label for="credentials">{{__('pages.Retain_Key_Public_Key')}}</label>
                            <input name="credentials[retain_key]" class="form-control" value="{{ $paymentGateway->credentials['retain_key']??''}}"  />
                        </div> 
                        <div class="form-group mt-4 d-none paddle nocod">
                            <label for="credentials">{{__('pages.webhook_Seceret')}}</label>
                            <input name="credentials[webhook_secret]" class="form-control" value="{{ $paymentGateway->credentials['webhook_secret']??'' }}" />
                        </div>
                        <div class="form-group mt-4 nocod">
                            <label for="mode">{{__('pages.environment')}} <span class="text-danger">*</span></label>
                            <select name="mode" class="form-select">
                                <option value="1" {{ $paymentGateway->mode ? 'selected' : '' }}>{{__('pages.live')}}</option>
                                <option value="0" {{ !$paymentGateway->mode ? 'selected' : '' }}>{{__('pages.test')}}</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="status">{{__('pages.status')}} <span class="text-danger">*</span></label>
                            <select name="status" class="form-select">
                                <option value="1" {{ $paymentGateway->status ? 'selected' : '' }}>{{__('pages.active')}}</option>
                                <option value="0" {{ !$paymentGateway->status ? 'selected' : '' }}>{{__('pages.inactive')}}</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="is_default">{{__('pages.set_as_default')}}</label>
                            <input type="checkbox" style="position: relative; top: 2px; left: 5px;" name="is_default" {{ $paymentGateway->is_default ? 'checked' : '' }} />
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">{{__('pages.save')}}</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')  
    <script>
        $('#type').on('change', function(){
            if($(this).val() == 'cod' || $(this).val() == 'eft'){
                $('.nocod').addClass('d-none');
            }
            else{
                $('.nocod').removeClass('d-none');
            }
            if($(this).val() == 'paddle'){
                $('.paddle').removeClass('d-none');
            }
            else{
                $('.paddle').addClass('d-none');
            }
        });
        $('#type').trigger('change');
    </script>
@endpush 