@extends('layouts.admin-auth')
@section('content')
    <div class="row">

        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.login') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <h2>{{__('pages.sign_In')}}</h2>
                                <p>{{__('pages.enter_your_email_and_password_to_login')}}</p>

                            </div>
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger mb-2">
                                    {{ $error }}
                                </div>
                            @endforeach
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">{{__('pages.email')}}</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <label class="form-label">{{__('pages.password')}}</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-12  d-flex justify-content-between ">
                                <div class="mb-3">
                                    <div class="form-check form-check-primary form-check-inline">
                                        <input class="form-check-input me-3" type="checkbox" id="form-check-default">
                                        <label class="form-check-label" for="form-check-default">
                                            {{__('pages.remember_me')}}
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <a href="{{ route('admin.forgot-password') }}"
                                        class="text-primary text-primary-hover">{{__('pages.forgot_password')}}?</a>
                                </div>

                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" type="submit">{{__('pages.SIGN_IN')}}</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
