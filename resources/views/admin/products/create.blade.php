@extends('layouts.admin')

@section('breadcrumb',  __('pages.products'))

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
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
    </style>
@endpush

@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row mb-4">
                        <div class="col-8">
                            <h4>{{__('pages.add_new_product')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-info">{{__('pages.go_back')}}</a>
                        </div>
                    </div>

                    {{-- errors --}}
                    @include('partials.errors')

                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name">{{__('pages.name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="{{__('pages.name')}}" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="image">{{__('pages.image')}} <span class="text-danger">*</span></label>
                                    <input class="form-control file-upload-input" type="file" id="image"
                                        name="image" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="stockQuantity">{{__('pages.stock_quantity')}} <span class="text-danger">*</span></label>
                                    <input name="stock_quantity" id="stockQuantity" class="form-control" placeholder="3"
                                        required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="price">{{__('pages.default_price')}} <span class="text-danger">*</span></label>
                                    <input name="price" id="price" class="form-control" placeholder="4500" required>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="categories">{{__('pages.categories')}} <span class="text-danger">*</span></label>
                                    <select name="category_id" id="categories" class="form-control">
                                        <option value="">{{__('pages.select_categories')}}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="subCategories">{{__('pages.sub_categories')}} <span class="text-danger"></span></label>
                                    <select name="sub_category_id" id="subCategories" class="form-control">
                                        <option value="">{{__('pages.select_sub-category')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <div><label for="tags">{{__('pages.tags')}} <span class="text-danger">*</span></label></div>
                                    <select name="tags_id[]" id="tags" class="form-control" multiple>
                                        <option value="">{{__('pages.select_tags')}}</option>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="slug">{{__('pages.slug')}} <span class="text-danger"></span></label>
                                    <input name="slug" id="slug" class="form-control" placeholder="{{__('pages.slug')}}"
                                        value="">
                                </div>
                            </div>

                            <div class="col-6">
                                <label for="is_active">{{__('pages.status')}}</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">{{__('pages.active')}}</option>
                                    <option value="0">{{__('pages.inactive')}}</option>
                                </select>
                            </div>


                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description">{{__('pages.description')}} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" placeholder="{{__('pages.description')}}" rows="5" required></textarea>
                                </div>
                            </div>
                            {{-- multiple image upload --}}
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="images">{{__('pages.images')}}</label>
                                    <div id="image-upload-container">
                                        <div class="input-group mb-2">
                                            <input class="form-control file-upload-input" type="file" name="images[]">
                                            <button type="button"
                                                class="btn btn-danger remove-image-button">{{__('pages.remove')}}</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add-image-button">{{__('pages.add_more_images')}}</button>
                                </div>
                            </div>

                            <script></script>



                            <hr>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>{{__('pages.variants')}}</h5>
                                    <div id="variants-container">
                                        <div class="variant-form row mb-4">
                                            <div class="col-2">
                                                <input type="text" name="variants[0][sku]" class="form-control"
                                                    placeholder="SKU" required>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="variants[0][price]" class="form-control"
                                                    placeholder="{{__('pages.price')}}" required>
                                            </div>
                                            <div class="col-2">
                                                <input type="number" name="variants[0][stock]" class="form-control"
                                                    placeholder="{{__('pages.stock')}}" required>
                                            </div>
                                            <!-- Attribute values will be added here dynamically -->
                                            <div class="col-4 attribute-values">
                                                <!-- Attribute values input fields will be dynamically added here -->
                                            </div>
                                            <div class="col-2">
                                                <button disabled type="button"
                                                    class="btn btn-danger btn-remove-variant">{{__('pages.remove_variant')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-variant" class="btn btn-success">{{__('pages.add_variant')}}</button>
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
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        $(document).ready(function() {
            var additionalQuil = new Quill('#additional-description-container', {
                theme: 'snow' // or 'bubble' theme
            });
            var additionalInfoQuil = new Quill('#additional-info-container', {
                theme: 'snow' // or 'bubble' theme
            });
            var shipping = new Quill('#shipping', {
                theme: 'snow' // or 'bubble' theme
            });
            // $('#categories').select2();
            $('#tags').select2();
            // $('#subCategories').select2();

            let attributeIndex = 0;
            let variantIndex = 1;
            let attributes = [];

            // Load subcategories based on selected category
            $('#categories').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        url: '{{ url('admin/get-subcategories') }}/' + categoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#subCategories').empty();
                            $('#subCategories').append(
                                '<option value="">Select Sub-Category</option>');
                            $.each(data, function(key, value) {
                                $('#subCategories').append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subCategories').empty();
                    $('#subCategories').append('<option value="">Select Sub-Category</option>');
                }
            });

            // Load attributes based on selected subcategory
            $('#subCategories').on('change', function() {
                var subCategoryId = $(this).val();
                if (subCategoryId) {
                    $.ajax({
                        url: '{{ url('admin/get-attributes') }}/' + subCategoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#variants-container .attribute-values').empty();
                            attributes = data; // Store attributes for later use in variants

                            // Add attributes to the first variant form
                            addAttributesToVariantForm($(
                                '.variant-form:first .attribute-values'), 0);
                        }
                    });
                } else {
                    $('#variants-container .attribute-values').empty();
                    attributes = [];
                }
            });

            // Function to add attributes to a variant form
            // function addAttributesToVariantForm(container, index) {
            //     container.empty();
            //     $.each(attributes, function(key, attribute) {
            //         container.append(`
        //             <input type="text" name="variants[${index}][attributes][${attribute.id}]" class="form-control mb-2" placeholder="${attribute.attribute_name}" required>
        //         `);
            //     });
            // }
            function addAttributesToVariantForm(container, index) {
                container.empty();

                // Determine the number of attributes per row (e.g., 2 or 3)
                const attributesPerRow = 3;
                let attributeHtml = '';

                $.each(attributes, function(key, attribute) {
                    if (key % attributesPerRow === 0 && key !== 0) {
                        // Close the previous row if it's not the first row
                        attributeHtml += '</div>';
                    }
                    if (key % attributesPerRow === 0) {
                        // Start a new row
                        attributeHtml += '<div class="row mb-2">';
                    }

                    attributeHtml += `
                        <div class="col-${12 / attributesPerRow}">
                            <input type="text" name="variants[${index}][attributes][${attribute.id}]" class="form-control mb-2" placeholder="${attribute.attribute_name}"  required>
                        </div>
                    `;
                });

                // Close the last row if it was started
                if (attributes.length % attributesPerRow !== 0) {
                    attributeHtml += '</div>';
                }

                container.append(attributeHtml);
            }
            // Add new variant form
            $('#add-variant').on('click', function() {
                let newVariantForm = `
                    <div class="variant-form row mb-4">
                        <div class="col-2">
                            <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU" required>
                        </div>
                        <div class="col-2">
                            <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="{{__('pages.price')}}" required>
                        </div>
                        <div class="col-2">
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="{{__('pages.stock')}}" required>
                        </div>
                        <div class="col-4 attribute-values">
                            <!-- Attribute values input fields will be dynamically added here -->
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-remove-variant">{{__('pages.remove_variant')}}</button>
                        </div>
                    </div>
                `;

                $('#variants-container').append(newVariantForm);
                addAttributesToVariantForm($('.variant-form:last .attribute-values'), variantIndex);
                variantIndex++;
            });

            // Remove variant form
            $(document).on('click', '.btn-remove-variant', function() {
                $(this).closest('.variant-form').remove();
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            //  upload  multipl images
            document.getElementById('add-image-button').addEventListener('click', function() {
                var container = document.getElementById('image-upload-container');
                var inputGroup = document.createElement('div');
                inputGroup.className = 'input-group mb-2';

                var input = document.createElement('input');
                input.type = 'file';
                input.name = 'images[]';
                input.className = 'form-control file-upload-input';

                var removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-danger remove-image-button';
                removeButton.innerText = "{{__('pages.remove')}}";

                removeButton.addEventListener('click', function() {
                    container.removeChild(inputGroup);
                });

                inputGroup.appendChild(input);
                inputGroup.appendChild(removeButton);
                container.appendChild(inputGroup);
            });

            document.querySelectorAll('.remove-image-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    var container = document.getElementById('image-upload-container');
                    container.removeChild(button.parentElement);
                });
            });

            $("form").submit(function() {
                var content = document.querySelector('input[name=additional-description-container]');
                content.value = additionalQuil.root.innerHTML;

                content = document.querySelector('input[name=additional-info-container]');
                content.value = additionalInfoQuil.root.innerHTML;

                content = document.querySelector('input[name=shipping]');
                content.value = shipping.root.innerHTML;
            });
        });
    </script>
@endpush
