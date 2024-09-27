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

                    <form action="{{ route('admin.forgot-password.request') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <h2>{{__('pages.forgot_password')}}</h2>
                                <p>{{__('pages.enter_your_email_address_to_reset_your_password')}}</p>

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
                                    <button class="btn btn-secondary w-100" type="submit">{{__('pages.send_Password_Reset_Link')}}</button>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <div class="mb-3">
                                    <a href="{{ route('admin.login') }}" class="text-primary text-primary-hover">{{__('pages.back_to_sign_In')}}</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
