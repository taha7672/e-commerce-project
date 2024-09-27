<?php use Illuminate\Support\Js; ?>
@extends('layouts.admin')

@section('breadcrumb', __('pages.products'))

@push('styles')
    <link href="{{ asset('admin-assets/plugins/src/filepond/filepond.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin-assets/plugins/src/filepond/FilePondPluginImagePreview.min.css')}}" rel="stylesheet">
    <link href="{{ asset('admin-assets/plugins/css/light/filepond/custom-filepond.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .fs-text { font-size: 1.2rem; }
        .lh-3rem { line-height: 3rem; }

        #mapper > div:nth-child(even) {
            background-color: var(--bs-gray-100);
        }
        #mapper > div {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
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
                        <h4>{{__('pages.import_products')}}</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="multiple-file-upload">
                            <input type="file"
                                class="filepond file-upload-single"
                                name="filepond"
                                multiple
                                data-allow-reorder="false"
                                data-max-file-size="10MB"
                                data-max-files="1"
                            />
                        </div>
                    </div>
                </div>

                <div class="row gap-2 mt-5 d-none" id="map-container">
                    <hr>

                    <div class="col-12">
                        <h4 class="fw-bold">Excel Mappings</h4>
                    </div>

                    <div class="col-12 row fw-bold fs-text">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-6">Column Name</div>
                                <div class="col-6">Map to Field</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 row gap-2" id="mapper">
                    </div>

                    <div class="col-12 mt-2 row">
                        <div class="col-12">
                            <button id="import-btn" class="btn btn-success float-end">Import</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<template id="col-normal">
    <div class="col-12">
        <div class="row">
            <div class="col-6 fs-text lh-3rem" data-map-label></div>
            <div class="col-6">
                <select role="button" class="form-select" data-map-name></select>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
    <script src="{{ asset('admin-assets/plugins/src/filepond/filepond.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/src/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/src/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/src/filepond/FilePondPluginImagePreview.min.js') }}"></script>
    <script src="{{ asset('admin-assets/plugins/src/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

    <script>
        const SERVER_PROPS = {
            uploadUrl: <?php echo Js::from(route('admin.products.import.upload')) ?>,
            saveUrl: <?php echo Js::from(route('admin.products.import.save')) ?>,
        };
    </script>
    <script src="{{ asset('assets/js/admin/products/import.js') }}"></script>
@endpush