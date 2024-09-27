@extends('layouts.admin')

@section('breadcrumb', __('pages.sliders'))
@section('content')

    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 mb-4">
                            <h4>{{__('pages.add_new_slider')}}</h4>
                        </div>
                    </div>
                    {{-- errors --}}
                    @include('partials.errors')
                    <form method="POST" action="{{ route('admin.slider.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="sub_title">{{__('pages.sub_title')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('sub_title') }}" name="sub_title" id="sub_title"
                                        class="form-control" placeholder="Sub title" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title">{{__('pages.title')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('title') }}" name="title" id="title"
                                        class="form-control" placeholder="Title" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title_color">{{__('pages.title_color')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('title_color') }}" name="title_color" id="title_color"
                                        class="form-control" placeholder="Title Color" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="button_link">{{__('pages.button_link')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('button_link') }}" name="button_link"
                                        id="button_link" class="form-control" placeholder="Link" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="button_text">{{__('pages.button_text')}} <span class="text-danger">*</span> </label>
                                    <input type="text" value="{{ old('button_text') }}" name="button_text"
                                        id="button_text" class="form-control" placeholder="Text" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label
                                        for="image">Image({{ config('business.productImgSize.slider_img_size.large_img_width') }}x{{ config('business.productImgSize.slider_img_size.large_img_height') }})
                                        <span class="text-danger">*</span></label>
                                    <input value="{{ old('image') }}" class="form-control file-upload-input" type="file"
                                        id="image" name="image" required>
                                </div>
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
