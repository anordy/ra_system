<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title')</title>

<link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('favicon.ico') }}" rel="icon" />

@livewireStyles()

<style>
    [x-cloak] { display: none !important; }
</style>