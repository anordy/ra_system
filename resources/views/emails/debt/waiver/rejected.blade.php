@component('mail::message')
# Hello {{ $payload['debt']->business->taxpayer->first_name }} {{ $payload['debt']->business->taxpayer->last_name }},

ZRA inform you that {{ $payload['debt']->taxtype->name }} debt waiver for {{ $payload['debt']->business->name }} at {{ $payload['debt']->location->name }} has been rejected.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
