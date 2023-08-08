<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.component.header')
</head>
<body>
<div class="wrapper mt-5">
    <div id="content">
        <div class="container">
            @include('layouts.component.messages')
            @yield('content')
        </div>
    </div>
</div>
@livewireScripts
@include('layouts.component.footer')
</body>
</html>
