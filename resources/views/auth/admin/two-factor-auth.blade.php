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

                    <form action="{{ route('admin.two-factor-auth.verify') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h2>{{__('pages.Two_Factor_Auth')}}</h2>
                                <p>{{__('pages.Enter_the_code_sent_to_your_email_to_verify_your_identity')}}.</p>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">{{__('pages.authentication_code')}}</label>
                                    <input autocomplete="off" type="text" class="form-control" name="auth_code"
                                        placeholder="{{__('pages.enter_your_authentication_code')}}" required />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" type="submit">{{__('pages.verify')}}</button>
                                </div>
                            </div>

                            <div class="col-12 text-center">
                                <div class="mb-3">
                                    <a href="{{ route('admin.login') }}" class="text-primary text-primary-hover">
                                        {{__('pages.back_to_sign_In')}}</a>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
