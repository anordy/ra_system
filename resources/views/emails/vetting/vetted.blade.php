@component('mail::message')
# Hello {{ $tax_return->taxpayer->first_name }},

Your Return for {{$tax_return->taxtype->name}} filing of {{$tax_return->financialMonth->name}}/{{$tax_return->financialMonth->year->code}} for {{$tax_return->business->name}} {{$tax_return->location->name}} has been approved. 

You will receive a control number shortly for payment, If not received please login to re-generate your control number.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
