@extends('layouts.admin')

@section('breadcrumb', 'Settings')

@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="row">
                    <div class="col-12">
                        {{-- errors --}}
                        @include('partials.errors')
                    </div>
                    <div class="col-12">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">Website setting</h4>
                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label>Brand Name <span class="text-danger">*</span> </label>
                                        <input type="text" name="setting[brand_name]" class="form-control"
                                            placeholder="Brand name" value="{{ getSetting('brand_name') }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Description <span class="text-danger">*</span> </label>
                                        <input type="text" name="setting[description]" class="form-control"
                                            placeholder="Description" value="{{ getSetting('description') }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="setting[email]" placeholder="Email" class="form-control"
                                            value="{{ getSetting('email') }}">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Phone Number</label>
                                        <input type="text" name="setting[phone_number]" placeholder="Phone"
                                            value="{{ getSetting('phone_number') }}" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Site Status</label>
                                        <select name="setting[site_status]" value="{{ getSetting('site_status') }}"
                                            class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    {{-- <div class="col-6 mb-3">
                                        <label>Two Factor Authentication (Users) </label>
                                        <select name="setting[two_factor_auth_user]" class="form-control"
                                            value="{{ getSetting('two_factor_auth_user') }}">
                                            <option value="1"
                                                {{ getSetting('two_factor_auth_user') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0"
                                                {{ getSetting('two_factor_auth_user') == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label>Two Factor Authentication (Admin) </label>
                                        <select name="setting[two_factor_auth_admin]"
                                            value="{{ getSetting('two_factor_auth_admin') }}" class="form-control">
                                            <option value="1"
                                                {{ getSetting('two_factor_auth_admin') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0"
                                                {{ getSetting('two_factor_auth_admin') == 0 ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div> --}}
                                    <div class="col-6 mb-3">
                                        <label>Email Verification Status</label>
                                        <select name="setting[email_verification_status]"
                                            value="{{ getSetting('email_verification_status') }}" class="form-control">
                                            <option value="1"
                                                {{ getSetting('email_verification_status') == 1 ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="0"
                                                {{ getSetting('email_verification_status') == 0 ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>Site Url</label>
                                        <input type="text" name="setting[site_url]" value="{{ getSetting('site_url') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>Address</label>
                                        <input type="text" name="setting[address]" value="{{ getSetting('address') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>City</label>
                                        <input type="text" name="setting[city]" value="{{ getSetting('city') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>State</label>
                                        <input type="text" name="setting[state]" value="{{ getSetting('state') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>Country</label>
                                        <input type="text" name="setting[country]" value="{{ getSetting('country') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label>Postal Code</label>
                                        <input type="text" name="setting[postal_code]"
                                            value="{{ getSetting('postal_code') }}" class="form-control">
                                    </div>
                                    <div class="text-end">
                                        <button class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">Logo</h4>
                            <form method="POST" action="{{ route('admin.settings.update.logo') }}"
                                enctype='multipart/form-data'>
                                @csrf
                                <div class="col-6 mb-3">
                                    <label>Company Logo</label>
                                    <input type="file" name="logo_url" id="logo_url" class="form-control"
                                        accept="image/*" onchange="upload_check()">
                                    <input type="hidden" value="{{ getSetting('logo_url') }}" name="logo_name">
                                    @if (!empty(getSetting('logo_url')))
                                        <img src="{{ url(getSetting('logo_url')) }}" alt="" width="100px">
                                    @endif
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">Language & currency</h4>
                            <form method="POST" action="{{ route('admin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label>Language</label>
                                    <select name="setting[selected_language_id]" class="form-control">
                                        @foreach ($languages as $language)
                                            <option value="{{ $language->id }}"
                                                {{ getSetting('selected_language_id') == $language->id ? 'selected' : '' }}>
                                                {{ $language->language_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Currency</label>
                                    <select name="setting[selected_currencies_id]" class="form-control">
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}"
                                                {{ getSetting('selected_currencies_id') == $currency->id ? 'selected' : '' }}>
                                                {{ $currency->currency_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-6 mt-4">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">Changes & Tax</h4>
                            <form method="POST" action="{{ route('admin.settings.update') }}">
                                @csrf
                                <div class="mb-3">
                                    <label>VAT Amount</label>
                                    <input type="text" name="setting[vat_amount]"
                                        value="{{ getSetting('vat_amount') }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Shipping Amount</label>
                                    <input type="text" name="setting[shipping_amount]"
                                        value="{{ getSetting('shipping_amount') }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Free Shipping Threshold</label>
                                    <input type="text" name="setting[free_shipping_threshold]"
                                        value="{{ getSetting('free_shipping_threshold') }}" class="form-control"
                                        required>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function upload_check() {
            let upl = document.getElementById("logo_url");
            let max = 3 * 1024 * 1024;

            if (upl.files[0].size > max) {
                alert("File too big!");
                upl.value = "";
            }
        }
    </script>
@endpush
