@component('mail::message')
# Hello {{ $installment->business->taxpayer->first_name }} {{ $installment->business->taxpayer->last_name }},

ZRA inform you that {{ $installment->taxtype->name }} debt installment request for {{ $installment->business->name }} at {{ $installment->location->name }} has been approved. Please log into your account to generate a control number for payment.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
