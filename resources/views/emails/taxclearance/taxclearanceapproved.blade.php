@component('mail::message')
# Hello {{ $payload[0]->business->taxpayer->first_name }},

Your request for tax clearance application is approved, please find attached document below.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
