@extends('layouts.admin')

@section('breadcrumb',  __('pages.email_templates'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/src/table/datatable/datatables.css')}}">
@endpush

@section('content')
<div class="middle-content container-xxl p-0">
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            <div class="widget-content widget-content-area br-8">
                <div class="row mb-4">
                    <div class="col-8">
                        <h4>{{__('pages.email_templates')}}</h4>
                    </div>
                </div>

				<a href="{{ route('admin.email-templates.create') }}" class="btn btn-primary mb-3">{{__('pages.create_new_template')}}</a>

				@if ($templates->isEmpty())
					<p>{{__('pages.no_email_templates_found')}}.</p>
				@else
					<div class="table-responsive">
					<!--  -->
					<table id="template-list" class="table dt-table-hover" style="width:100%">
						<thead>
							<tr>
								<th>{{__('pages.name')}}</th>
								<th>{{__('pages.subject')}}</th>
								<th>{{__('pages.actions')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($templates as $template)
								<tr>
									<td>{{ $template->name }}</td>
									<td>{{ $template->subject }}</td>
									<td> 
										<a class="btn btn-info btn-icon _effect--ripple waves-effect waves-light" href="{{ route('admin.email-templates.edit', $template->id) }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
										<form action="{{ route('admin.email-templates.destroy', $template->id) }}" method="POST" style="display:inline-block;">
											@csrf
											@method('DELETE')
											<button class="btn btn-danger btn-icon m-2 delete-blog _effect--ripple waves-effect waves-light" onclick="return confirm('Are you sure you want to delete this template?')" type="button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button> 
										</form>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<!--  -->
				</div>
				@endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts') 
    <script src="{{asset('admin-assets/js/custom.js')}}"></script>
    <script src="{{asset('admin-assets/plugins/src/table/datatable/datatables.js')}}"></script>
    <script>
        $(function() {
            templateList = $('#template-list').DataTable( );

            multiCheck(templateList);
        })
    </script>
@endpush