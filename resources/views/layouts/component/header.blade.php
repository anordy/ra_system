<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'ZRB') }} | @yield('title')</title>

<link href="{{ asset('plugins/fontawesome/css/fontawesome.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::to('css/style.css') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}" />
@livewireStyles()

<style>
    .swal2-container {
        z-index: 20000 !important;
    }

    [x-cloak] { display: none !important; }

    .tabs .tab-item {
        border: none;
        background: none;
        padding-bottom: 5px;
        padding-right: 0;
        padding-left: 0;
        margin-right: 22px;
    }
    .tabs .tab-item:hover {
        color: #97363a;
    }
    .tabs .tab-item:focus, .tabs .tab-item:focus-visible {
        outline: none;
    }
    .tabs .tab-item.active {
        border-bottom: 3px solid #97363a;
        font-weight: bold;
    }
</style>