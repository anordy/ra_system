@component('mail::message')
# Hello {{ $deregister->business->taxpayer->first_name }},

Your ZRA business de-registration for {{ $deregister->business->name }} @if ($deregister->location)
    , {{ $deregister->location->name }}
@endif has been rejected.

Login into your account to for more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent