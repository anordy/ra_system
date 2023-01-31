@component('mail::message')
# Hello {{ $payload['tax_change']->business->taxpayer->first_name }} {{ $payload['tax_change']->business->taxpayer->last_name }},

According to your tax type change request, on {{ \Carbon\Carbon::parse($payload['tax_change']->effective_date)->format('d-m-Y') }} you will be changed from: <br>
    
<span>- {{ $payload['tax_change']->fromTax->name }} to @if ($payload['tax_change']->subvat)
    {{ $payload['tax_change']->subvat->name }}
@else
    {{ $payload['tax_change']->toTax->name }}
@endif</span> <br>

Please login into your account to view more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
