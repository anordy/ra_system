<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src fonts.googleapis.com 'self' 'nonce-custom_style'; script-src 'self'  'nonce-custom_script'; font-src 'self' fonts.gstatic.com; img-src 'self' data: ">
    <title>{{ config('app.name', 'CRDB') }} | @yield('title')</title>
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <style nonce="custom_style">
        body {
            background-image: url("{{ URL::to('/images/crdb1.webp') }}");
            background-size: cover;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.1);
            font-size: 12px;
        }
    
        .middle-box {
            max-width: 400px;
            z-index: 100;
            margin: 0 auto;
            padding-top: 40px;
        }

        .middle-box-wd {
            min-width: 550px !important;
            max-width: 600px !important;
        }

        .invalid-feedback{
            white-space: nowrap;overflow: scroll
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    <script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    @yield('scripts')
</body>
</html>
