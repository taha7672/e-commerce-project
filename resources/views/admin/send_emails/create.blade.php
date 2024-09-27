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
.select2-container--default .select2-selection--multiple, .select2-container .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__arrow{
	height: 48px !important;
}
.select2-container .select2-selection--multiple .select2-selection__rendered{
	line-height:34px;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
	line-height: 48px !important;
}
</style><link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <div class="row mb-4">
                    <div class="col-8">
                        <h4>{{__('pages.send_email_template')}}</h4>
                    </div>
                </div>
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
					<div class="container"> 
						<form action="{{ route('admin.send-emails.store') }}" method="POST">
							@csrf
							<div class="form-group mt-2">
								<label for="send_to_all">{{__('pages.send_to_all_users')}}</label>
								<input type="checkbox" name="send_to_all" id="send_to_all"  />
							
							</div>
							<div class="form-group mt-2">
								<label for="user_emails">{{__('pages.emails')}}</label>
								<select multiple name="user_emails[]" id="user_emails" class="form-select" >
									@if(count($users))
										@foreach($users as $user)
											<option value="{{ $user->email}}">{{$user->name}} ({{$user->email}})</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="form-group mt-2">
								<label for="template_id">{{__('pages.template_name')}}</label>
								<select name="template_id" required onchange="funcSelectTemplate(this)" id="template_id" class="form-select">
									<option value="">{{__('pages.select_template')}}</option>
									@if(count($templates))
										@foreach($templates as $template)
											<option data-value="{{ json_encode($template) }}" value="{{ $template->id }}">{{$template->name}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<div class="form-group mt-2">
								<label for="subject">{{__('pages.subject')}}</label>
								<input type="text" name="subject" id="subject" readonly class="form-control" required>
							</div>
							<div class="form-group mt-2">
								<label for="body">{{__('pages.body')}} </label>
								<textarea name="body" class="form-control wysiwyg" rows="10" required></textarea>
							</div>
							<div class="form-group mt-2">
								<label for="order_id">{{__('pages.select_order')}}</label>
								<select name="order_id" id="order_id" class="form-select">
									<option value="0" >{{__('pages.select_order')}}</option>
									@if(count($orders))
										@foreach($orders as $order)
											<option value="{{ $order->id }}">#{{$order->order_num}}</option>
										@endforeach
									@endif
								</select>
							</div>
							<button type="submit" class="btn btn-primary mt-2">{{__('pages.send_email')}}</button>
						</form>
					</div>
            </div>
        </div>
    </div>
</div>
  

@endsection

@push('scripts') 
	<script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script> 

function matchOptFunc(params, data) {
    if ($.trim(params.term) === '') {
      return data;
    }

    if (typeof data.text === 'undefined') {
      return null;
    }

    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
      var modifiedData = $.extend({}, data, true);
      return modifiedData;
    }
    return null;
  }

  function tagValidate(params) {
    function validateEmail(email) {
      return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    }; 

    if (validateEmail(params.term) == null) {
      return null;
    }
    return {id: params.term, text: params.term}
  }

  function userTemplate(state) {
    if (!state.id) { 
      return state.text; 
    }

    let html = $('<span><i class="fa fa-user user-badge"></i> ' + state.text + '</span>');
    return html;
  }
  $('#order_id').select2();
	$("#user_emails").select2({
			closeOnSelect : false,
			placeholder : "",
			allowHtml: false,
			allowClear: false,
			tags:  true,
			minimumInputLength: 1,
			matcher: matchOptFunc,
			createTag: tagValidate,
			templateSelection: userTemplate,
			tokenSeparators: [',', ' ']
		}).on('select2:selecting', e => $(e.currentTarget).data('scrolltop', $('.select2-results__options').scrollTop()))
					.on('select2:select', e => $('.select2-results__options').scrollTop($(e.currentTarget).data('scrolltop')))
					.on('select2:unselecting', e => $(e.currentTarget).data('scrolltop', $('.select2-results__options').scrollTop()))
					.on('select2:unselect', e => $('.select2-results__options').scrollTop($(e.currentTarget).data('scrolltop')));
		function funcSelectTemplate(elem){
			if($(elem).val()){
				var select = document.getElementById("template_id");
				var selectedOption = select.selectedOptions[0];
				var dataValue = selectedOption.getAttribute("data-value");
				let template = JSON.parse(dataValue);
				window.editor.setData(template.body);
				$('#subject').val(template.subject); 
			}
			else{
				window.editor.setData('');
				$('#subject').val(''); 
			}
		}
        const shortcodes = @json($shortcodes);
		ClassicEditor
			.create(document.querySelector('.wysiwyg')) 
			.then((editor) => {
				window.editor = editor;
					 // Listen for changes in the editor and update the textarea
				editor.model.document.on('change:data', () => {
					document.querySelector('.wysiwyg').value = editor.getData();
				});
				
				window.editor.enableReadOnlyMode('editor');
 
			})
			.catch(error => {
				console.error(error);
			});
	</script>
@endpush