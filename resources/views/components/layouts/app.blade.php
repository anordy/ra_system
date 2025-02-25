<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.component.header')
</head>
<body>
<div class="wrapper">
    @if(auth()->check())
        @include('layouts.component.sidebar')
    @endif
    <div id="content">
        @if(auth()->check())
            @include('layouts.component.top-nav')
        @endif
        <div class="container-fluid">
            @include('layouts.component.messages')
            @include('layouts.component.back-nav')
            @yield('content')
        </div>
    </div>
</div>
@include('layouts.component.footer')
</body>
</html>
