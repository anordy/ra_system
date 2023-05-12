<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src fonts.googleapis.com 'self' 'nonce-custom_style'; script-src 'self' 'nonce-custom_script'; font-src 'self' fonts.gstatic.com; img-src 'self' data: ">
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
            Sorry, the results for <span class="text-lowercase">{{ $exception->getMessage() }}</span> could not be found. If you think something is broken, report a problem to admin.
        </p>
        <div class="btns">
            <a href="{{route('home')}}">Home</a>
            <a href="{{url()->previous()}}">Back</a>
        </div>
    </div>
</body>

</html>
