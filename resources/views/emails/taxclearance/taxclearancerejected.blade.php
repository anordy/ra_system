@component('mail::message')
# Hello {{ $payload->taxpayer->first_name }},

Your request for tax clearance application was rejected, please pay off all debts to be clear for approval.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
