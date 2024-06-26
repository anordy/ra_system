@extends("layouts.master")

@section("title", "403 error")

@section("content")
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
    <div class="text-center">
        <div class="content">
            <h2 class="header" data-text="403">
                403
            </h2>
            <h3 data-text="Opps! Page not found">
                Forbidden
            </h3>
            <p>
                You do not have permission to access this resource.
            </p>
            <div class="btns">
                <a href="{{url()->previous()}}">Back</a>
            </div>
        </div>
    </div>
@endsection
