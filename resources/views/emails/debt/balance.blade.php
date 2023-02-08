@component('mail::message')
# Hello {{ $payload['debt']->business->taxpayer->first_name }} {{ $payload['debt']->business->taxpayer->last_name }},

ZRA inform you that {{ $payload['debt']->taxtype->name }} debt for {{ $payload['debt']->business->name }} at {{ $payload['debt']->location->name }} debt has been cleared.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
