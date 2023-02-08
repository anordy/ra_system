@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Your ZRA business registration for {{ $business->name }} has been approved..

Thanks,<br>
{{ config('app.name') }}
@endcomponent