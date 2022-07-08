@component('mail::message')
# Hello {{ $business->taxpayer->first_name }},

Your ZRB temporary business closure for {{ $business->name }} has been approved.

Thanks,<br>
{{ config('app.name') }}
@endcomponent