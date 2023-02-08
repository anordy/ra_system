@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Welcome to ZRA, please use the following details to access your account.

@component('mail::panel')
Reference No: **{{ $taxpayer->reference_no }}<br>**
Password: **{{ $code }}**
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
