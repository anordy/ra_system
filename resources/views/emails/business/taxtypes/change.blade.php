@component('mail::message')
# Hello {{ $payload['tax_change']->business->taxpayer->first_name }} {{ $payload['tax_change']->business->taxpayer->last_name }},

According to your tax type change request, on {{ $payload['tax_change']->effective_date }} you will be changed from: <br>
    
<span>- {{ $payload['tax_change']->fromTax->name }} to {{ $payload['tax_change']->toTax->name }}</span> <br>

Please login into your account to view more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
