@extends("layouts.master")

@section("title", "500 error")

@section("content")
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
    <div class="text-center">
        <div class="content">
            <h2 class="header" data-text="500">
                500
            </h2>
            <h3 data-text="Opps! Page not found">
                Opps! Internal Server Error
            </h3>
            <p>
                Sorry, Something went wrong, please contact the administrator for help, Please report a problem to admin.
            </p>
            <div class="btns">
                <a href="{{url()->previous()}}">Back</a>
            </div>
        </div>
    </div>
@endsection
