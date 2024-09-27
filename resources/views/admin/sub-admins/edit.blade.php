@extends('layouts.admin')

@section('breadcrumb',  __('pages.sub_admins'))
@section('content')
    <div class="middle-content container-xxl p-0">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-8">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12 mb-4">
                            <h4>{{__('pages.update_sub_admin')}}</h4>
                        </div>
                    </div>
                    {{-- errors --}}
                    @include('partials.errors')
                    <form method="POST" action="{{route('admin.sub-admins.update',$admin->id)}}">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="name">{{__('pages.name')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Name" required value="{{$admin->name}}">
                                </div>
                            </div>
                            <div class="col-6">
                                 <div class="mb-3">
                                    <label for="name">{{__('pages.surname')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="surname" id="surname" class="form-control" placeholder="Surname" required value="{{$admin->surname}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="email">{{__('pages.email')}} <span class="text-danger">*</span> </label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" required value="{{$admin->email}}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="password">{{__('pages.password')}}</label>
                                    <input type="text" name="password" id="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="">{{__('pages.roles')}} <span class="text-danger">*</span></label>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{$role->name}}" id="role-{{$role->id}}" {{in_array($role->id,$admin->roles()->pluck('id')->toArray())?'checked':''}}>
                                        <label class="form-check-label" for="role-{{$role->id}}">{{$role->name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @can('manage-individual-permissions')
                            <div class="col-12">
                                <label for="">{{__('pages.permissions')}} <span class="text-danger"></span></label>
                                @foreach ($permissions as $permission)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{$permission->name}}" id="permission-{{$permission->id}}" {{in_array($permission->id,$admin->permissions()->pluck('id')->toArray())?'checked':''}}>
                                        <label class="form-check-label" for="permission-{{$permission->id}}">{{$permission->name}}</label>
                                    </div>
                                @endforeach
                            </div>
                            @endcan
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
