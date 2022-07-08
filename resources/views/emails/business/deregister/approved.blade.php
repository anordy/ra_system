@component('mail::message')
# Hello {{ $business->taxpayer->first_name }},

Your ZRB business de-registration for {{ $business->name }} has been approved.

Thanks,<br>
{{ config('app.name') }}
@endcomponent