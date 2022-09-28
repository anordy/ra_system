@component('mail::message')
# Greetings {{ $full_name }},

You have been registered into ZRB Staff Administration System. Please use credentials below to Log into the system.<br>

@component('mail::panel')
Email: {{ $email }}<br>
Password: {{ $password }}
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
