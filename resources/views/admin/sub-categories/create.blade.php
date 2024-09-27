@extends('layouts.admin')

@section('breadcrumb',  __('pages.categories'))
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
                            <h4>{{__('pages.add_new_sub_category')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{route('admin.sub-categories.index')}}" class="btn btn-info">{{__('pages.go_Back')}}</a>
                        </div>
                    </div>
                    {{-- errors --}}
                    @include('partials.errors')
                    <form method="POST" action="{{route('admin.sub-categories.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="category">{{__('pages.category')}}</label>
                                    <select name="category_id" id="category" class="form-control" >
                                        @if(count($category)>0)
                                            @foreach($category as $list)
                                                <option value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                                        @else
                                            <option value="">{{__('pages.select')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name">{{__('pages.name')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="{{__('pages.name')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="thumbnail">{{__('pages.image')}}</label>
                                    <input class="form-control file-upload-input" type="file" id="thumbnail" name="image">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="metaDesc">{{__('pages.description')}} <span class="text-danger">*</span> </label>
                                    <textarea name="description" id="metaDesc" class="form-control" placeholder="{{__('pages.description')}}" rows="5" required></textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="is_active">{{__('pages.status')}}</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">{{__('pages.active')}}</option>
                                    <option value="0">{{__('pages.inactive')}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5>{{__('pages.attributes')}} 
                                    <span data-toggle="tooltip" data-placement="right" title="Common attributes of Sub-Category like:
                                    - Mobile Attributes: Brand, Model, Storage, RAM, Camera, Battery, Color.
                                    - Mobile Variants: Different configurations of Storage and RAM, different Colors.">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </h5>
                                <div id="attributes-container">
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <input type="text" name="attributesName[]" class="form-control" placeholder="{{__('pages.attribute_name')}}" required>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger btn-remove-attribute">{{__('pages.remove')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-attribute" class="btn btn-success">{{__('pages.add_attribute')}}</button>
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

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category').select2();
            
            let attributeIndex = 1;

            $('#add-attribute').on('click', function() {
                $('#attributes-container').append(`
                    <div class="row mb-3">
                        <div class="col-5">
                            <input type="text" name="attributesName[]" class="form-control" placeholder="{{__('pages.attribute_name')}}" required>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-remove-attribute">{{__('pages.remove')}}</button>
                        </div>
                    </div>
                `);
                attributeIndex++;
            });

            $(document).on('click', '.btn-remove-attribute', function() {
                $(this).closest('.row').remove();
            });
        });
    </script>
@endpush
