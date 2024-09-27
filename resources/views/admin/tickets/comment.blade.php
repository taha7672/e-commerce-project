@extends('layouts.admin')

@section('breadcrumb', __('pages.tickets'))
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

.comments-container {
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 20px;
}

.comments-container {
    display: flex;
    flex-direction: column;
    gap: 1rem; /* Space between comments */
}

.comment-box {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.comment-avatar {
    flex-shrink: 0;
}

.comment-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.comment-content {
    flex-grow: 1;
    overflow: hidden;
    word-wrap: break-word; /* Ensures long words break onto the next line */
}

.comment-meta {
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.comment-meta strong {
    font-weight: bold;
    margin-right: 0.5rem;
}

.comment-time {
    font-size: 0.8rem;
    color: #888;
    white-space: nowrap; /* Prevents the time from breaking onto a new line */
}

.comment-content p {
    margin: 0;
    font-size: 1rem;
    color: #333;
    line-height: 1.5; /* Increases readability for long comments */
}

.comment-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
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
                        <h4>{{__('pages.add_comments')}}</h4>
                    </div>
                    <div class="col-4 text-end">
                        <a href="{{route('admin.tickets.index')}}" class="btn btn-info">{{__('pages.go_back')}}</a>
                    </div>
                </div>
                {{-- errors --}}
                @include('partials.errors')
                <form method="POST" action="{{route('admin.commit.save',$ticketId)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="mb-3">
                                <h6>{{__('pages.ticketDescription')}}</h6>
                                <p>{{$description}}</p>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="demo1">{{__('pages.comment')}}
                                    <span class="text-danger">*</span> </label>
                                <textarea name="comment" id="demo1" class="form-control" placeholder="Comment here..."
                                    rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                        <button class="btn btn-primary" type="submit">
                            {{__('pages.submit')}}
                        </button>
                    </div>
                        @if(!empty($comments))
                        <div class="col-12 ">
                              <h6>{{__('pages.comments')}}</h6>
                              <div class="comments-container">
    @foreach ($comments as $comment)
        <div class="comment-box">
            <div class="comment-avatar">
                <img alt="avatar" src="{{ asset('assets/images/user (5).svg') }}"  class="rounded-circle" />
            </div>
            <div class="comment-content">
                <div class="comment-meta">
                    <strong>{{ $comment->commentor_name }}</strong>
                   
                </div>
                <div class="comment-line">
                    <p>{{ $comment->comment }}</p>
                    <span class="text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
            </div>
             
        </div>

    @endforeach
    {{ $comments->links() }}

                            
                        </div>
                        @endif

                    </div>



                   
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

var uploadUrl = '{{$uploadUrl}}';
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