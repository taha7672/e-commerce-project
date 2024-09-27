{{-- @extends('layouts.app')
@push('styles')
<style>
    .alert.alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>
@endpush
@section('content')
<section class="pt-[200px] mb-150 max-md:mb-25">
    <div
      class="container relative"
      data-aos="fade-up"
      data-aos-offset="200"
      data-aos-duration="1000"
      data-aos-once="true"
    >
      <div class="mb-12 text-center max-w-[475px] mx-auto">
        <h2>
          Reset Password <br />
        </h2>
      </div>
      <div class="relative z-10 max-w-[510px] mx-auto">
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex max-md:flex-col -z-10">
          <div
            class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/30 blur-[145px]"
          ></div>
          <div
            class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/50 -ml-[170px] max-md:ml-0 blur-[145px]"
          ></div>
          <div
            class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/30 -ml-[170px] max-md:ml-0 blur-[145px]"
          ></div>
        </div>
        <div class="bg-white dark:bg-dark-200 rounded-medium p-2.5 shadow-nav">
          <div
            class="bg-white dark:bg-dark-200 border border-dashed rounded border-gray-100 dark:border-borderColour-dark p-12 max-md:px-5 max-md:py-7"
          >
            <form action="{{route('password.update')}}" method="POST">
                @include('partials.errors')
                @csrf
              <div class="grid grid-cols-12 gap-y-6 ">
                <div class="col-span-12">
                  <label
                    for="email"
                    class="block text-sm font-medium font-jakarta_sans text-paragraph dark:text-white mb-2"
                  >
                    Email
                  </label>
                  <input
                    autocomplete="off"
                    type="email"
                    name="email"
                    id="email"
                    readonly
                    placeholder="Email address"
                    class="block w-full text-sm rounded-[48px] border border-borderColour dark:border-borderColour-dark py-3.5 px-5 text-paragraph-light placeholder:text-paragraph-light dark:placeholder:text-paragraph-light outline-none bg-white dark:bg-dark-200 focus:border-primary duration-300 transition-all"
                    value="{{$email}}"
                    />
                    <input type="hidden" name="token" value="{{$token}}">
                </div>
                <div class="col-span-full">
                  <label
                    for="password"
                    class="block text-sm font-medium font-jakarta_sans text-paragraph dark:text-white mb-2"
                  >
                    Password
                  </label>
                  <input
                    autocomplete="off"
                    type="password"
                    name="password"
                    id="password"
                    placeholder="At least 8 character"
                    class="block w-full text-sm rounded-[48px] border border-borderColour dark:border-borderColour-dark py-3.5 px-5 text-paragraph-light   placeholder:text-paragraph-light outline-none bg-white dark:bg-dark-200 focus:border-primary duration-300 transition-all"
                  />

                </div>
                <div class="col-span-full">
                    <label
                      for="confirm_password"
                      class="block text-sm font-medium font-jakarta_sans text-paragraph dark:text-white mb-2"
                    >
                      Confirm Password
                    </label>
                    <input
                      autocomplete="off"
                      type="password"
                      name="password_confirmation"
                      id="confirm_password"
                      placeholder="confirm password"
                      class="block w-full text-sm rounded-[48px] border border-borderColour dark:border-borderColour-dark py-3.5 px-5 text-paragraph-light   placeholder:text-paragraph-light outline-none bg-white dark:bg-dark-200 focus:border-primary duration-300 transition-all"
                    />

                  </div>
                <div class="col-span-full ">
                  <button class="btn w-full block font-medium">Update password</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection --}}


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

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @include('partials.errors')
                        <div class="row">
                            <div class="col-md-12 mb-3">

                                <h2>Reset Password</h2>
                                <p>Enter your new password below.</p>

                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input autocomplete="off" type="email" class="form-control" name="email" readonly
                                        placeholder="Email address" value="{{ $email }}" required />
                                    <input type="hidden" name="token" value="{{ $token }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input autocomplete="off" type="password" class="form-control" name="password"
                                        placeholder="At least 8 characters" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input autocomplete="off" type="password" class="form-control"
                                        name="password_confirmation" placeholder="Confirm password" required />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-secondary w-100" type="submit">Update Password</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
