@component('mail::message')
# Hello {{ $tax_return->taxpayer->first_name }},

Your {{$tax_return->taxtype->name}} for filing month of {{$tax_return->financialMonth->name}}/{{$tax_return->financialMonth->year->code}} for {{$tax_return->business->name}} {{$tax_return->location->name}} have been rejected for correction. 

Please login to make required corrections.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
