@extends('layouts.admin')

@section('breadcrumb', __('pages.email_templates'))

@section('content')
<style>
.ck-editor__editable {
    min-height: 500px;
	margin-bottom: 20px;
}
.modal-content{
	background: white;
}
</style>
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <div class="row mb-4">
                    <div class="col-8">
                        <h4>{{__('pages.create_email_template')}}</h4>
                    </div>
                </div>
					<div class="container"> 
						<form action="{{ route('admin.email-templates.store') }}" method="POST">
							@csrf
							<div class="form-group mt-2">
								<label for="name">{{__('pages.template_name')}}</label>
								<input type="text" name="name" class="form-control" required>
							</div>
							<div class="form-group mt-2">
								<label for="subject">{{__('pages.subject')}}</label>
								<input type="text" name="subject" class="form-control" required>
							</div>
							<div class="form-group mt-2">
								<label for="body">{{__('pages.body')}} <a href="javascript:void(0)" data-toggle="modal" data-target="#shortcodesModal" onclick="$('#shortcodesModal').modal('show');" class="text-info">Add Shortcodes</a></label>
								<textarea name="body" class="form-control wysiwyg" rows="10" required></textarea>
							</div>
							<button type="submit" class="btn btn-primary">{{__('pages.create')}}</button>
						</form>
					</div>
            </div>
        </div>
    </div>
</div>
 

<!-- Modal -->
<div class="modal fade" id="shortcodesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{__('pages.available_shortcodes')}}</h5> 
      </div>
      <div class="modal-body">
        @if($shortcodes)
			@foreach($shortcodes as $shortcode => $description)
				<a href="javascript:void(0)" data-shortcode="[{{$shortcode}}]" class="text-info shortcode-button btn mt-2">[{{$shortcode}}]{{-- ({{ $description}})--}}</a> 
			@endforeach
		@endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#shortcodesModal').modal('hide')">{{__('pages.close')}}</button> 
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts') 
	<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
	<script>
		/* class MyUploadAdapter {
			constructor( loader ) {
				// The file loader instance to use during the upload.
				this.loader = loader;
			}

			// Starts the upload process.
			upload() {
				
				return new Promise((resolve, reject) => {
					const reader = this.reader = new window.FileReader();
					reader.addEventListener('load', () => {
						resolve({ default: reader.result });
					});
					reader.addEventListener('error', err => {
						reject(err);
					});
					reader.addEventListener('abort', () => {
						reject();
					});
					this.loader.file.then(file => {
						reader.readAsDataURL(file);
					});
				});
			}

			// Aborts the upload process.
			abort() { 
			}
		} */

		
		class MyUploadAdapter {
			 /**
			 * Creates a new adapter instance.
			 */
			constructor(loader, options) {
				this.loader = loader;
				this.options = options;
			}
			/**
			 * Starts the upload process.
			 *
			 * @see module:upload/filerepository~UploadAdapter#upload
			 */
			upload() {
				return this.loader.file
					.then(file => new Promise((resolve, reject) => {
					this._initRequest();
					this._initListeners(resolve, reject, file);
					this._sendRequest(file);
				}));
			}
			/**
			 * Aborts the upload process.
			 *
			 * @see module:upload/filerepository~UploadAdapter#abort
			 */
			abort() {
				if (this.xhr) {
					this.xhr.abort();
				}
			}
			/**
			 * Initializes the `XMLHttpRequest` object using the URL specified as
			 * {@link module:upload/uploadconfig~SimpleUploadConfig#uploadUrl `simpleUpload.uploadUrl`} in the editor's
			 * configuration.
			 */
			_initRequest() {
				const xhr = this.xhr = new XMLHttpRequest();
				xhr.open('POST', this.options.uploadUrl, true);
				xhr.responseType = 'json';
			}
			/**
			 * Initializes XMLHttpRequest listeners
			 *
			 * @param resolve Callback function to be called when the request is successful.
			 * @param reject Callback function to be called when the request cannot be completed.
			 * @param file Native File object.
			 */
			_initListeners(resolve, reject, file) {
				const xhr = this.xhr;
				const loader = this.loader;
				const genericErrorText = `Couldn't upload file: ${file.name}.`;
				xhr.addEventListener('error', () => reject(genericErrorText));
				xhr.addEventListener('abort', () => reject());
				xhr.addEventListener('load', () => {
					const response = xhr.response;
					if (!response || response.error) {
						return reject(response && response.error && response.error.message ? response.error.message : genericErrorText);
					}
					const urls = response.url ? { default: response.url } : response.urls;
					// Resolve with the normalized `urls` property and pass the rest of the response
					// to allow customizing the behavior of features relying on the upload adapters.
					resolve({
						...response,
						urls
					});
				});
				// Upload progress when it is supported.
				/* istanbul ignore else -- @preserve */
				if (xhr.upload) {
					xhr.upload.addEventListener('progress', evt => {
						if (evt.lengthComputable) {
							loader.uploadTotal = evt.total;
							loader.uploaded = evt.loaded;
						}
					});
				}
			}
			/**
			 * Prepares the data and sends the request.
			 *
			 * @param file File instance to be uploaded.
			 */
			_sendRequest(file) {
				// Set headers if specified.
				const headers = this.options.headers || {};
				// Use the withCredentials flag if specified.
				const withCredentials = this.options.withCredentials || false;
				for (const headerName of Object.keys(headers)) {
					this.xhr.setRequestHeader(headerName, headers[headerName]);
				}
				this.xhr.withCredentials = withCredentials;
				// Prepare the form data.
				const data = new FormData();
				data.append('upload', file);
				data.append('_token', this.options.token);
				// Send the request.
				this.xhr.send(data);
			}
		}
		
		ClassicEditor
			.create(document.querySelector('.wysiwyg')) 
			.then((editor) => {
				window.editor = editor;
					 // Listen for changes in the editor and update the textarea
				editor.model.document.on('change:data', () => {
					document.querySelector('.wysiwyg').value = editor.getData();
				});
				editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
					option = {uploadUrl: '{{ route("admin.upload-editor-media") }}', token: "{{ csrf_token() }}"};
					return new MyUploadAdapter( loader, option );
				};


                // Handle shortcode button clicks
                document.querySelectorAll('.shortcode-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const shortcode = this.getAttribute('data-shortcode');
                        editor.model.change(writer => {
                            const insertPosition = editor.model.document.selection.getFirstPosition();
                            writer.insertText(shortcode, insertPosition);
                        });
                        modal.style.display = 'none'; // Hide the modal after insertion
                    });
                });
			})
			.catch(error => {
				console.error(error);
			});
	</script>
@endpush