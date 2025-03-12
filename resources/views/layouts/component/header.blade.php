<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'CRDB') }} | @yield('title')</title>
<link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />

<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" />
@yield('css')
@stack('styles')
@livewireStyles(['nonce' => 'custom_style'])
@livewireScripts(['nonce' => 'custom_script'])