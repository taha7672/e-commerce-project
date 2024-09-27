@extends('layouts.admin')

@section('breadcrumb',  __('pages.settings'))

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{asset('admin-assets/plugins/src/table/datatable/datatables.css')}}">
@endpush
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="row">
                    <div class="col-12">
                        {{-- errors --}}
                        @include('partials.errors')
                    </div>
                    <div class="col-lg-6">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">{{__('pages.profile')}}</h4>
                            <form method="POST" action="{{route('admin.update-profile')}}">
                                @csrf
                                <div class="mb-3">
                                    <label for="name">{{__('pages.name')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$user->name}}" required>
                                </div>
                                 <div class="mb-3">
                                    <label for="name">{{__('pages.surname')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="surname" id="surname" class="form-control" placeholder="Surname" value="{{$user->surname}}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email">{{__('pages.email')}} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{$user->email}}" readonly>
                                </div>
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <label class="form-label" style="font-size: 1em;">{{__('pages.enable_2_factor_authentication')}}</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="two_factor_auth" value="0">
                                        <input class="form-check-input" type="checkbox" id="twoFactorAuth" name="two_factor_auth" value="1" style="width: 3.5em; height: 2em;"
                                            @if($user->two_factor_auth) checked @endif>
                                        <label class="form-check-label" for="twoFactorAuth"></label>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        {{__('pages.submit')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="widget-content widget-content-area br-8">
                            <h4 class="mb-4">{{__('pages.update_password')}}</h4>
                            <form method="POST" action="{{route('admin.update-password')}}">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password">{{__('pages.current_password')}} <span class="text-danger">*</span> </label>
                                    <input type="password" name="current_password" id="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password">{{__('pages.new_password')}}<span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password">{{__('pages.confirm_password')}} <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" id="confirm_password" class="form-control" required>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        {{__('pages.submit')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('admin-assets/plugins/src/table/datatable/datatables.js')}}"></script>
    <script>
        $(document).on('click','.delete-category',function(){
            let delete_form=$(this).parent('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                   delete_form[0].submit();
                }
            })
        });
    </script>
@endpush
