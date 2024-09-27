@extends('layouts.admin')

@section('breadcrumb',  __('pages.categories'))

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
                            <h4>{{__('pages.categories')}}</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a class="btn btn-success" href="{{route('admin.categories.create')}}">{{__('pages.add_new')}} </a>
                        </div>
                    </div>
                    <table id="categories" class="table dt-table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>{{ __('tables.no') }}</th>
                                <th>{{ __('tables.name') }}</th>
                                <th>{{ __('tables.image') }}</th>
                                <th>{{ __('tables.description') }}</th>
                                <th>{{ __('tables.status') }}</th>
                                <th class="no-content">{{ __('tables.action') }}</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($categories)>0)
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{$category->id}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>
                                        @php
                                            $imagePath = public_path($category->image);
                                        @endphp

                                        @if($category->image && file_exists($imagePath))
                                            <img 
                                                src="{{ asset($category->image) }}" 
                                                alt="{{ $category->name }}" 
                                                width="100" 
                                                onerror="this.onerror=null;this.src='{{ asset('images/dummy-image-portrait.jpg') }}';"
                                            >
                                        @else
                                            <img 
                                                src="{{ asset('assets/images/dummy-image-portrait.jpg') }}" 
                                                alt="{{ $category->name }}" 
                                                width="100"
                                            >
                                        @endif
                                        </td>
                                        <td>{{$category->description}}</td>
                                        <td>{{ $category->is_active ? __('pages.active') : __('pages.inactive') }}</td>                                        <td>
                                            <a class="btn btn-info btn-icon" href="{{route('admin.categories.edit',$category->id)}}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{route('admin.categories.destroy',$category->id)}}" class="d-inline" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-icon m-2 delete-blog" type="button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('admin-assets/plugins/src/table/datatable/datatables.js')}}"></script>
    <script>
        $('#categories').DataTable({
            "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
        "<'table-responsive'tr>" +
        "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "{{__('pages.showing_page')}} _PAGE_ {{__('pages.of')}} _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "{{__('pages.search')}}...",
            "sLengthMenu": "{{__('pages.results')}} :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 10
        });
        $(document).on('click','.delete-blog',function(){
            let delete_form=$(this).parent('form');
            Swal.fire({
                title: "{{ __('pages.are_you_sure') }}?",
                text: "{{ __('pages.you_want_to_delete_this') }}?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('pages.yes,_delete_it') }}",
                cancelButtonText: "{{ __('pages.cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                   delete_form[0].submit();
                }
            })
        });
    </script>
@endpush
