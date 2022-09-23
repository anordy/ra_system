@component('mail::message')
# Hello {{ $deregister->business->taxpayer->first_name }},

Your ZRB business de-registration for {{ $deregister->business->name }} @if ($deregister->location)
    , {{ $deregister->location->name }}
@endif has been approved.

Thanks,<br>
{{ config('app.name') }}
@endcomponent