@component('mail::message')
# Hello {{ $payload['debt']->business->taxpayer->first_name }} {{ $payload['debt']->business->taxpayer->last_name }},

ZRB inform you that {{ $payload['debt']->taxtype->name }} debt waiver for {{ $payload['debt']->business->name }} at {{ $payload['debt']->location->name }} has been approved. You will receive a new control number with adjusted amount within a few minutes.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
