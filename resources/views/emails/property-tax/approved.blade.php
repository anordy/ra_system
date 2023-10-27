@component('mail::message')
# Hello {{ $property->taxpayer->first_name }},

You have successfully registered your property with unit registration number {{$this->property->urn ?? 'N/A'}} for property tax. You will receive payment control number shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
