<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.component.header')


</head>

<body>
    <div class="wrapper">
        @include('layouts.component.sidebar')

        <div id="content">
           @include('layouts.component.top-nav')
            <div class="container-fluid">
                @include('layouts.component.messages')

                @yield('content')
            </div>
        </div>
    </div>

    @livewireScripts()
    @include('layouts.component.footer')
</body>

</html>
