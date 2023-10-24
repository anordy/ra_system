@component('mail::message')
# Hello {{ $property->responsible->first_name }},

You have successfully registered your property with unit registration number {{$this->property->urn}} for property tax. You will receive payment control number shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
