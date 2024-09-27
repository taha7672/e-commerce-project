<?php use Illuminate\Support\Js; ?>
@extends('layouts.admin')

@section('breadcrumb', __('pages.currency_settings'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/plugins/src/table/datatable/datatables.css') }}">
    <style>
        label.error {color: red;}

        .modal-content{
            background: white;
        }

        #cur-main > div {
            margin-bottom: 1rem;
        }

        .sider {
            width: 2rem;
            display: flex;
            align-content: center;
            justify-content: center;
            align-items: center;
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
                        <h4>{{__('pages.currency_settings')}}</h4>
                    </div>
                </div>

                <div class="d-flex">
                    <div style="flex: 1;">
                        <div class="row">
                            <div class="col-4">
                                <h5>{{__('pages.currency_name')}}</h5>
                            </div>
                            <div class="col-4">
                                <h5>{{__('pages.currency_code')}}</h5>
                            </div>
                            <div class="col-4">
                                <h5>{{__('pages.exchange_rate_to_USD')}}</h5>
                            </div>
                        </div>
                    </div>

                    <div class="sider"></div>
                </div>

                <form class="mb-4" id="cur-main"></form>

                <div class="row mb-4">
                    <div class="col-6">
                        <button onclick="add();" class="btn btn-primary">{{__('pages.add_currency')}}</button>
                    </div>
                    <div class="col-6">
                        <button id="saveBtn" onclick="saveCur()" class="btn btn-success float-end">{{__('pages.save')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="template">
    <div data-container=":uuid" class="d-flex">
        <div style="flex: 1;">
            <div class="row">
                <div class="col-4">
                    <input name="currency_name[:uuid]" type="text" class="form-control" value=":name" required />
                </div>
                <div class="col-4">
                    <input name="currency_code[:uuid]" type="text" class="form-control" value=":code" required />
                </div>
                <div class="col-4">
                    <input name="exchange_rate_to_usd[:uuid]" type="text" class="form-control" value=":rate" required />
                </div>
            </div>
        </div>

        <div class="sider">
            <i onclick="deleteCur(':uuid')" class="fa fa-trash text-danger" role="button"></i>
        </div>
    </div>
</template>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js" integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        const SERVER_PROPS = {
            state: <?php echo Js::from($currency); ?>
        };

        function genUuid() {
            return ([1e7]+-1e3+-4e3+-8e3+-1e11)
                .replace(/[018]/g,c =>(c^(window.crypto||window.msCrypto).getRandomValues(new Uint8Array(1))[0]&15>>c/4)
                .toString(16));
        }

        function getTemplate() {
            return document.querySelector('#template').content.children[0].outerHTML;
        }

        function add(currency) {
            let name = currency ? currency.currency_name : null;
            let code = currency ? currency.currency_code : null;
            let rate = currency ? currency.exchange_rate_to_usd : null;
            let uuid = genUuid();

            let tpl = getTemplate()
                .replaceAll(':uuid', uuid || '')
                .replaceAll(':name', name || '')
                .replaceAll(':code', code || '')
                .replaceAll(':rate', rate || '');

            $('#cur-main').append(tpl);
        }

        function deleteCur(uuid) {
            let code = $(`[name="currency_code[${uuid}]"]`).val();
            if ( !code ) {
                $(`[data-container="${uuid}"]`).remove();
                return;
            }

            Swal.fire({
                title: "Do you really want to delete the currency?",
                showCancelButton: true,
                confirmButtonText: "Yes",
                icon: 'warning',
            }).then((result) => {
                if ( !result.isConfirmed ) {
                    return;
                }

                $.ajax({
                    method: "DELETE",
                    url: "{{ route('admin.settings.currency.delete') }}",
                    data: {code},
                    success: function (res) {
                        if (res.status_code == 422) {
                            popError(res.message);
                            return;
                        }

                        $(`[data-container="${uuid}"]`).remove();

                        Swal.fire({
                            title: 'Success !!!',
                            text: "Currency is deleted successfully",
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
            });

            
        }

        function init() {
            let data = SERVER_PROPS.state;
            if ( !data ) {
                return add();
            }

            data.forEach((c) => add(c));
        }

        function saveCur() {
            let form = document.querySelector('#cur-main');

            if ( !$(form).valid() ) {
                return;
            }

            let fd = new FormData(form);
            let btn = document.querySelector('#saveBtn');

            $.ajax({
                method: "POST",
                url: "{{ route('admin.settings.currency.save') }}",
                data: fd,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    btn.setAttribute('disabled', true);
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving';
                },
                success: function (res) {
                    if (res.status_code == 422) {
                        const regex_code = /currency_code\.[a-zA-Z0-9-]+/;
                        const match_code = res.message.match(regex_code);

                        if (match_code) {
                            popError(res.message.replace(match_code, 'Country Code'));
                            return;    
                        }

                        const regex_rate = /exchange_rate_to_usd\.[a-zA-Z0-9-]+/;
                        const match_rate = res.message.match(regex_rate);

                        if (match_rate) {
                            popError(res.message.replace(match_rate, 'Exchange Rate to USD'));
                            return;    
                        }

                        popError('All Fields are required');
                        return;
                    }

                    Swal.fire({
                        title: 'Success !!!',
                        text: "Currencies are saved successfully",
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
        }

        function popError(msg = "Something went wrong") {
            Swal.fire({
                title: 'Error !!!',
                text: msg,
                icon: 'error',
                timer: 3000,
            });
        }

        $(function() {
            init();

            // $("#cur-main").validate({
            //     rules: {
            //         currency_code: {
            //             maxlength: 3
            //         },
            //         exchange_rate_to_usd: "number",
            //     }
            // });
        });
    </script>
@endpush