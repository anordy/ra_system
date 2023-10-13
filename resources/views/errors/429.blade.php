<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>429 Error Page</title>
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
</head>
<body>
<div id="error-page">
    <div class="content">
        <h2 class="header" data-text="429">
            429
        </h2>
        <h4 data-text="Too Many Requests">
            Too Many Requests
        </h4>
        <p>
            Too Many Requests, Please try again later.
        </p>
        <div class="btns">
            <a href="{{route('home')}}">Home</a>
            <a href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>
</body>
</html>
