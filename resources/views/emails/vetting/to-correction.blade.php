@component('mail::message')
# Hello {{ $tax_return->taxpayer->first_name }},

Your {{$tax_return->taxtype->name}} for filing month of {{$tax_return->financialMonth->name}}/{{$tax_return->financialMonth->year->code}} for {{$tax_return->business->name}} {{$tax_return->location->name}} has not been approved, pending for corrections.

Please login to make necessary corrections. 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
