@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Your ZRB business de-registration for {{ $business->name }} requires corrections, login into your account to for more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent