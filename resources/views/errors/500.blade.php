<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>500 Error Page</title>
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
</head>
<body>
<div id="error-page">
    <div class="content">
        <h2 class="header" data-text="500">
            500
        </h2>
        <h4 data-text="Opps! Page not found">
            Opps! Internal Server Error
        </h4>
        <p>
            Sorry, Something went wrong, Could you please contact our administrator for assistance?, Please report a problem to admin.
        </p>
        <div class="btns">
            <a href="{{route('home')}}">Home</a>
            <a href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>
</body>
</html>
