@component('mail::message')
# Hello {{ $payload['branch']->taxpayer->first_name }} {{ $payload['branch']->taxpayer->last_name }},

Your ZRB branch registration for {{ $payload['branch']->name }} has corrections. Please log in to view more information.

Thanks,<br>
{{ config('app.name') }}
@endcomponent