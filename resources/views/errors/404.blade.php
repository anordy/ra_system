<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>404 Error Page</title>
    <link rel="stylesheet" href="{{ asset('css/error.css') }}">
</head>

<body>
    <div id="error-page">
        <div class="content">
            <h2 class="header" data-text="404">
                404
            </h2>
            <h4 data-text="Opps! Page not found">
                Opps! Page not found
            </h4>
            <p>
                Sorry, the page you're looking for doesn't exist.
            </p>
            <div class="btns">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ url()->previous() }}">Back</a>
            </div>
        </div>
    </div>
</body>

</html>
