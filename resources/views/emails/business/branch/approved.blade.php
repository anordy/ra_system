@component('mail::message')
# Hello {{ $payload['branch']->taxpayer->first_name }} {{ $payload['branch']->taxpayer->last_name }},

Your ZRA new branch registration with name {{ $payload['branch']->name }} for business {{ $payload['branch']->business->name }} has been approved.

Thanks,<br>
{{ config('app.name') }}
@endcomponent