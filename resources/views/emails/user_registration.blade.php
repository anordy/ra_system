@component('mail::message')
# Greetings {{ $full_name }},

You have been registered into ZPC System. Please use credentials below to Log into the system.<br>

@component('mail::panel')
username: {{ $username }}<br>
password: {{ $password }}
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
