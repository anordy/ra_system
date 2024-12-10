@component('mail::message')
# Hello {{ $business->name ?? 'N/A' }},

Welcome to ZRA, please use the following details to access your account.

@component('mail::panel')
Z Number: **{{ $business->ztn_number ?? 'N/A' }}<br>**
Password: **{{ $code }}**
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
