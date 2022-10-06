
@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Please find exit-minutes report and preliminary report for audit conducted on your business with name **{{ $payload[1]->business->name }}** below.
Thanks,<br>
{{ config('app.name') }}
@endcomponent
