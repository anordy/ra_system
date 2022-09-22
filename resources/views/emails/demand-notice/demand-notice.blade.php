@component('mail::message')
# Hello {{ $payload['debt']->business->taxpayer->first_name }} {{ $payload['debt']->business->taxpayer->last_name }},

This is your debt demand notice for {{ $payload['debt']->business->name }} at {{ $payload['debt']->location->name }}. Payment of the amount owing should be made within {{ $payload['paid_within_days'] }} working days.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
