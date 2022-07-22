@component('mail::message')
# Hello {{ $payload['business']->taxpayer->first_name }} {{ $payload['business']->taxpayer->last_name }},

Your request to update business details for {{ $payload['business']->name }} has been accepted.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
