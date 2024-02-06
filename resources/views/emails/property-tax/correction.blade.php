@component('mail::message')
# Hello {{ $property->taxpayer->first_name }},

Your property registration for {{ $property->name  }} requires correction. Please login to your account to update your registration.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
