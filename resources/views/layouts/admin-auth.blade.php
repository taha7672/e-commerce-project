<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SwapSkill</title>
    <link rel="icon" type="image/x-icon" href="../src/assets/img/favicon.ico"/>
    <link href="{{asset('admin-assets/layouts/css/light/loader.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin-assets/layouts/css/dark/loader.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{asset('admin-assets/layouts/loader.js')}}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{asset('admin-assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('admin-assets/layouts/css/light/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin-assets/css/light/authentication/auth-boxed.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{asset('admin-assets/layouts/css/dark/plugins.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin-assets/css/dark/authentication/auth-boxed.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="form">

    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <div class="auth-container d-flex">

        <div class="container mx-auto align-self-center">

            @yield('content')

        </div>

    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{asset('admin-assets/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->


</body>
</html>
