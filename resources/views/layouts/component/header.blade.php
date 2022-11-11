<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src fonts.googleapis.com 'self' 'nonce-custom_style'; script-src 'self' 'nonce-custom_script'; font-src 'self' fonts.gstatic.com">

<title>{{ config('app.name', 'ZIDRAS') }} | @yield('title')</title>

<link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />

<link href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" />

@livewireStyles()

@yield('css')
