@component('mail::message')
# Hello {{ $payload['return']->business->taxpayer->first_name }} {{ $payload['return']->business->taxpayer->last_name }},

This is your return demand notice for {{ $payload['return']->business->name }} at {{ $payload['return']->location->name }}. Payment of the amount owing should be made within 30 working days.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
