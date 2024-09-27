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

        .blog-thumbnail {
            height: 100px;
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .file-upload-input {
            flex: 1;
        }

        .remove-image-button {
            margin-left: 10px;
        }

        .image-preview {
            margin-left: 10px;
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
                            <h4>{{__('pages.update_product')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-info">{{__('pages.go_back')}}</a>
                        </div>
                    </div>

                    {{-- errors --}}
                    @include('partials.errors')

                    <form method="POST" action="{{ route('admin.products.update', $product->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name">{{__('pages.name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="{{__('pages.name')}}" required value="{{ old('name', $product->name) }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="image">{{__('pages.image')}}</label>
                                    <input class="form-control file-upload-input" type="file" id="image"
                                        name="image">
                                    @php
                                        $imagePath = public_path($product->image);
                                    @endphp

                                    @if ($product->image && file_exists($imagePath))
                                        <img src="{{ asset($product->image) }}" class="blog-thumbnail"
                                            alt="{{ $product->name }}" width="100"
                                            onerror="this.onerror=null;this.src='{{ asset('images/dummy-image-portrait.jpg') }}';">
                                    @else
                                        <img src="{{ asset('assets/images/dummy-image-portrait.jpg') }}" alt="No Image"
                                            width="100">
                                    @endif
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="price">{{__('pages.price')}} <span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" class="form-control"
                                        placeholder="4500" required
                                        value="{{ old('price', $product->convertedPrice()) }}" />
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="categories">{{__('pages.categories')}} <span class="text-danger">*</span></label>
                                    <select name="category_id" id="categories" class="form-control" required>
                                        <option value="">{{__('pages.select_categories')}}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->categories->contains($category->id) ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="subCategories">{{__('pages.sub_categories')}} <span class="text-danger"></span></label>
                                    <select name="sub_category_id" id="subCategories" class="form-control" >
                                        <option value="">{{__('pages.select_sub-categories')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="tags">{{__('pages.tags')}} <span class="text-danger">*</span></label>
                                    <select name="tags_id[]" id="tags" class="form-control" multiple required>
                                        <option value="">{{__('pages.select_tag')}}</option>
                                        @php
                                            $productTagIds = $product->productTags->pluck('tags_id')->toArray();
                                        @endphp
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ in_array($tag->id, $productTagIds) ? 'selected' : '' }}>
                                                {{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="slug">Slug <span class="text-danger"></span></label>
                                    <input type="text" name="slug" id="slug" class="form-control"
                                        placeholder="slug" value="{{ old('slug', $product->slug) }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description">{{__('pages.description')}} <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Additional Description --}}
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additional-description-container">Additioanl Description</label>
                                    <div id="additional-description-container"></div>
                                    <input type="hidden" name="additional-description-container">
                                </div>
                            </div>
                            {{-- Additional Information --}}
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="additional-info-container">Additioanl Information</label>
                                    <div id="additional-info-container"></div>
                                    <input type="hidden" name="additional-info-container">
                                </div>
                            </div>
                            {{-- Additional Information --}}
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="shipping">Shipping & Return</label>
                                    <div id="shipping"></div>
                                    <input type="hidden" name="shipping">
                                </div>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="is_active">{{__('pages.status')}}</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ old('is_active', $product->is_active) ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ !old('is_active', $product->is_active) ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <hr>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="images">{{__('pages.images')}}</label>
                                    <div id="image-upload-container">
                                        @foreach ($product->images as $image)
                                            <div class="input-group mb-2" data-image-id="{{ $image->id }}">
                                                <input type="hidden" name="existing_image_ids[]"
                                                    value="{{ $image->id }}">
                                                <input class="form-control file-upload-input" type="file"
                                                    name="images[]" data-image-id="{{ $image->id }}">
                                                <button type="button" class="btn btn-danger remove-image-button">{{__('pages.remove_image')}}</button>
                                                <div class="image-preview">
                                                    <img src="{{ asset($image->image_path) }}" class="blog-thumbnail"
                                                        width="100"
                                                        onerror="this.onerror=null;this.src='{{ asset('images/dummy-image-portrait.jpg') }}';">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="add-image-button">{{__('pages.add_more_images')}}
                                        </button>
                                    <input type="hidden" name="removed_image_ids" id="removed-image-ids">
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h5>Variants</h5>
                                    <div id="variants-container">
                                        @foreach ($product->productVariants as $index => $variant)
                                            <div class="variant-form row mb-4">
                                                <div class="col-2">
                                                    <input type="hidden" name="variants[{{ $index }}][id]"
                                                        value="{{ old('variants[' . $index . '][id]', $variant->id) }}">
                                                    <input type="text" name="variants[{{ $index }}][sku]"
                                                        class="form-control" placeholder="SKU" required
                                                        value="{{ old('variants[' . $index . '][sku]', $variant->sku) }}">

                                                    <small>
                                                        @if ($variant->is_default)
                                                            {{__('pages.this_is_default_variant')}}
                                                        @endif
                                                    </small>
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" name="variants[{{ $index }}][price]"
                                                        class="form-control" placeholder="Price" required
                                                        value="{{ old('variants[' . $index . '][price]', $variant->convertedPrice()) }}">
                                                </div>
                                                <div class="col-2">
                                                    <input type="number" name="variants[{{ $index }}][stock]"
                                                        class="form-control" placeholder="Stock" required
                                                        value="{{ old('variants[' . $index . '][stock]', $variant->stock) }}">
                                                </div>
                                                @php
                                                    $variantArray = $variant->toArray();
                                                    $attributeValues = $variantArray['attribute_values'];
                                                @endphp
                                                @foreach ($attributeValues as $attribute)
                                                    <div class="col-2">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control"
                                                                placeholder= "{{ $attribute['value'] }}" required
                                                                value="{{ $attribute['value'] }}">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" id="add-variant" class="btn btn-success">{{__('pages.add_variant')}}</button>
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

            $('#tags').select2();

            // Initialize subcategories based on selected category
            @php $categorie = $product->categories->toArray(); @endphp
            var selectedSubCategory = @json(isset($categorie[0]) ? $categorie[0]['pivot']['sub_category_id'] : null);
            var selectedCategory = $('#categories').val();
            if (selectedCategory) {
                fetchSubCategories(selectedCategory, selectedSubCategory);
            }

            $('#categories').on('change', function() {
                var categoryId = $(this).val();
                fetchSubCategories(categoryId);
            });

            $('#subCategories').on('change', function() {
                var subCategoryId = $(this).val();
                fetchAttributes(subCategoryId);
            });

            function fetchSubCategories(categoryId, selectedSubCategory = null) {
                if (categoryId) {
                    $.ajax({
                        url: '{{ url('admin/get-subcategories') }}/' + categoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#subCategories').empty();
                            $('#subCategories').append(
                                '<option value="">Select Sub-Categories</option>');
                            $.each(data, function(key, value) {
                                $('#subCategories').append('<option value="' + value.id + '">' +
                                    value.name + '</option>');
                            });
                            if (selectedSubCategory) {
                                $('#subCategories').val(selectedSubCategory).trigger('change');
                            }
                        }
                    });
                }
            }

            function fetchAttributes(subCategoryId) {
                if (subCategoryId) {
                    $.ajax({
                        url: '{{ url('admin/get-attributes') }}/' + subCategoryId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            attributes = data; // Store globally for variant forms
                            refreshVariantForms();
                        }
                    });
                }
            }

            function refreshVariantForms() {
                $('.attribute-values').empty();
                $('#variants-container .variant-form').each(function(index, form) {
                    addAttributesToVariantForm($(form).find('.attribute-values'), index);
                });
            }

            function addAttributesToVariantForm(container, index) {
                if (attributes) {
                    $.each(attributes, function(key, attribute) {
                        container.append(`
                            <div class="col-2">
                                <input type="text" name="variants[${index}][attributes][${attribute.id}]" class="form-control" placeholder="${attribute.attribute_name}" required>
                            </div>
                        `);
                    });
                }
            }

            $('#add-variant').on('click', function() {
                var index = $('#variants-container .variant-form').length;
                var newVariantHtml = `
                    <div class="variant-form row mb-4">
                        <div class="col-2">
                            <input type="text" name="variants[${index}][sku]" class="form-control" placeholder="SKU" required>
                        </div>
                        <div class="col-2">
                            <input type="number" name="variants[${index}][price]" class="form-control" placeholder="{{__('pages.price')}}" required>
                        </div>
                        <div class="col-2">
                            <input type="number" name="variants[${index}][stock]" class="form-control" placeholder="{{__('pages.stock')}}" required>
                        </div>
                        <div class="col-4 attribute-values"></div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger btn-remove-variant">{{__('pages.remove_variant')}}</button>
                        </div>
                    </div>
                `;
                $('#variants-container').append(newVariantHtml);
                addAttributesToVariantForm($('#variants-container .variant-form:last .attribute-values'),
                    index);
            });

            $('#variants-container').on('click', '.btn-remove-variant', function() {
                $(this).closest('.variant-form').remove();
            });

            // images edit

            const imageContainer = document.getElementById('image-upload-container');
            const removedImageIds = document.getElementById('removed-image-ids');

            document.querySelectorAll('.remove-image-button').forEach(button => {
                button.addEventListener('click', function() {
                    const imageGroup = this.closest('.input-group');
                    const imageId = imageGroup.getAttribute('data-image-id');
                    imageGroup.remove();
                    removedImageIds.value += (removedImageIds.value ? ',' : '') +
                        imageId;
                });
            });

            document.getElementById('add-image-button').addEventListener('click', function() {
                const newImageInput = document.createElement('div');
                newImageInput.className = 'input-group mb-2';
                newImageInput.innerHTML = `
            <input class="form-control file-upload-input" type="file" name="images[]">
            <button type="button" class="btn btn-danger remove-image-button">{{__('pages.remove')}}</button>
        `;
                imageContainer.appendChild(newImageInput);

                newImageInput.querySelector('.remove-image-button').addEventListener('click',
                    function() {
                        newImageInput.remove();
                    });
            });

            $("form").submit(function(){
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
