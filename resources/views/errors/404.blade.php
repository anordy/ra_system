@extends("layouts.master")

@section("title", "404 error")

@section("content")
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
    <div class="text-center">
        <div class="content">
            <h2 class="header" data-text="400">
                404
            </h2>
            <h3 data-text="Opps! Page not found">
                Opps! Page not found
            </h3>
            <p>
                Page not found.
            </p>
            <div class="btns">
                <a href="{{url()->previous()}}">Back</a>
            </div>
        </div>
    </div>
@endsection
