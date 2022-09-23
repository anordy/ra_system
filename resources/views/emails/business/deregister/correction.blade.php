@component('mail::message')
# Hello {{ $deregister->business->taxpayer->first_name }},

Your ZRB business de-registration for {{ $deregister->business->name }} @if ($deregister->location)
    , {{ $deregister->location->name }}
@endif requires corrections.

Login into your account to for more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent