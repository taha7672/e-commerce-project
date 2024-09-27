@extends('layouts.admin-auth')

@section('content')
    <div class="row">

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    {{-- Display success and error messages --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @include('partials.errors')
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <h2>{{__('pages.reset_password')}}</h2>
                                <p>{{__('pages.enter_your_new_password_below')}}.</p>

                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">{{__('pages.email')}}</label>
                                    <input autocomplete="off" type="email" class="form-control" name="email" readonly
                                        placeholder="{{__('pages.email_address')}}" value="{{ $email }}" required />
                                    <input type="hidden" name="token" value="{{ $token }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">{{__('pages.password')}}</label>
                                    <input autocomplete="off" type="password" class="form-control" name="password"
                                        placeholder="{{__('pages.at_least_8_characters')}}" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">{{__('pages.confirm_password')}}</label>
                                    <input autocomplete="off" type="password" class="form-control"
                                        name="password_confirmation" placeholder="{{__('pages.confirm_password')}}" required />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" type="submit">{{__('pages.update_password')}}</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
