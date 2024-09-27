@extends('layouts.admin')

@section('breadcrumb', __('pages.tags'))
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
                            <h4>{{__('pages.add_new_tag')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a href="{{route('admin.tags.index')}}" class="btn btn-info">{{__('pages.go_back')}}</a>
                        </div>
                    </div>
                    {{-- errors --}}
                    @include('partials.errors')
                    <form method="POST" action="{{route('admin.tags.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="title">{{__('pages.name')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="{{__('pages.name')}}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="thumbnail">{{__('pages.image')}} </label>
                                    <input class="form-control file-upload-input" type="file" id="thumbnail" name="image" >
                                </div>
                            </div>
                          
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="metaDesc">{{__('pages.description')}} <span class="text-danger">*</span> </label>
                                    <textarea name="description" id="metaDesc" class="form-control" placeholder="{{__('pages.description')}}" rows="5" required></textarea>
                                </div>
                            </div>
                           
                            <div class="col-6">
                                <label for="published">{{__('pages.status')}}</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1">{{__('pages.active')}}</option>
                                    <option value="0">{{__('pages.inactive')}}</option>
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
    <script>
        $('.select2TagList').select2({
            tags: true,
            tokenSeparators: [',', ' '], // Allow comma or space to create new tags
            placeholder: 'Select tags or type to add new ones'
        });

        var uploadUrl = '{{$uploadUrl}}' ;
        CKEDITOR.ClassicEditor.create(document.getElementById("summary"), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', '|',
                    'bulletedList', 'numberedList', '|',

                    'undo', 'redo',

                    'alignment', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                ],
                shouldNotGroupWhenFull: true
            },

            removePlugins: [
                'ExportPdf',
                'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType'
            ]
        });

        CKEDITOR.ClassicEditor.create(document.getElementById("blog-content"), {
            ckfinder: {
                    uploadUrl: uploadUrl,
                },
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', '|',
                    'bulletedList', 'numberedList', '|',
                    'undo', 'redo',
                    '-',
                     'fontColor', '|',
                    'alignment', '|',
                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                    'sourceEditing'
                ],
                shouldNotGroupWhenFull: true
            },

            removePlugins: [
                'ExportPdf',
                'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'Base64UploadAdapter',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType'
            ]
        });
    </script>
@endpush
