@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Your ZRB business registration for {{ $business->name }} requires corrections on the following area,

@component('mail::panel')
{{ $message }}
@endcomponent

Login into your account to for more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent