@component('mail::message')
# Greetings {{ $username }},

Please use the code below for Two factor Verification.<br>

@component('mail::panel')
code: {{ $code }}<br>
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
