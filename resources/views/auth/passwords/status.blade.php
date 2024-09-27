@extends('layouts.admin-auth')

@section('content')
    <section class="pt-[200px] mb-150 max-md:mb-25">
        <div class="container relative" data-aos="fade-up" data-aos-offset="200" data-aos-duration="1000" data-aos-once="true">
            <div class="relative z-10 max-w-[510px] mx-auto">
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 flex max-md:flex-col -z-10">
                    <div
                        class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/30 blur-[145px]">
                    </div>
                    <div
                        class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/50 -ml-[170px] max-md:ml-0 blur-[145px]">
                    </div>
                    <div
                        class="max-1xl:w-[335px] max-1xl:h-[335px]  1xl:w-[442px] 1xl:h-[442px]  rounded-full bg-primary-200/30 -ml-[170px] max-md:ml-0 blur-[145px]">
                    </div>
                </div>
                <div class="bg-white dark:bg-dark-200 rounded-medium p-2.5 shadow-nav">
                    <div
                        class="bg-white dark:bg-dark-200 border border-dashed rounded border-gray-100 dark:border-borderColour-dark p-12 max-md:px-5 max-md:py-7">
                        @if ($error)
                            <h2 class="text-red text-center" style="color:red">
                                {{ $error }}
                            </h2>
                        @endif
                        @if ($success)
                            <h2 class="text-green text-center" style="color: green">
                                {{ $success }}
                            </h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
