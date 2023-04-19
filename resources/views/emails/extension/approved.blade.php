@component('mail::message')
# Hello {{ $extension->business->taxpayer->first_name }} {{ $extension->business->taxpayer->last_name }},

ZRA inform you that {{ $extension->taxtype->name }} debt extension request for {{ $extension->business->name }} at {{ $extension->location->name }} has been approved. Please use previously provided control number to complete payments before {{ $extension->extend_to }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
