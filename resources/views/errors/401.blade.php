<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>401 Error Page </title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="{{asset('css/error.css')}}">
</head>
<body>
<div id="error-page">
    <div class="content">
        <h2 class="header" data-text="401">
            401
        </h2>
        <h4 data-text="Opps! Page not found">
            Unauthorised
        </h4>

        <p>
            You don't have permission to access this resource.
        </p>
        <div class="btns">
            <a href="{{route('home')}}">Home</a>
            <a href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</div>
</body>
</html>
