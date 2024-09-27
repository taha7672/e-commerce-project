@extends('layouts.admin')

@section('breadcrumb',  __('pages.sliders'))

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

        .blog-thumbnail {
            height: 100px;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')

    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.update_slider')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{ route('admin.slider.index') }}" class="btn btn-info">{{__('pages.go_back')}}</a>
                        </div>
                    </div>

                    {{-- errors --}}
                    @include('partials.errors')

                    <form method="POST" action="{{ route('admin.slider.update', $slider->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="sub_title">{{__('pages.sub_title')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('sub_title', $slider->sub_title) }}"
                                        name="sub_title" id="sub_title" class="form-control" placeholder="Sub title"
                                        required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title">{{__('pages.title')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('title', $slider->title) }}" name="title"
                                        id="title" class="form-control" placeholder="Title" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title_color">{{__('pages.title_color')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('title_color',$slider->title_color) }}" name="title_color" id="title_color"
                                        class="form-control" placeholder="Title Color" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="button_link">{{__('pages.button_link')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('button_link', $slider->link) }}" name="button_link"
                                        id="button_link" class="form-control" placeholder="Link" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="button_text">{{__('pages.button_text')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('button_text', $slider->button_text) }}"
                                        name="button_text" id="button_text" class="form-control" placeholder="Text"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label
                                        for="image">Image({{ config('business.productImgSize.slider_img_size.large_img_width') }}x{{ config('business.productImgSize.slider_img_size.large_img_height') }})</label>
                                    <input class="form-control file-upload-input" type="file" id="image"
                                        name="image">
                                    @php
                                        $imagePath = public_path($slider->small_image);
                                    @endphp

                                    @if ($slider->small_image && file_exists($imagePath))
                                        <img src="{{ asset($slider->small_image) }}" class="blog-thumbnail"
                                            alt="{{ $slider->name }}" width="100"
                                            onerror="this.onerror=null;this.src='{{ asset('images/dummy-image-portrait.jpg') }}';">
                                    @else
                                        <img src="{{ asset('assets/images/dummy-image-portrait.jpg') }}" alt="No Image"
                                            width="100">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">{{__('pages.submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
