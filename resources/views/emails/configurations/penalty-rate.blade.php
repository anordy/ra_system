@component('mail::message')
# System Warning,

Penalty rates for the year {{ $payload['currentYear'] }} have not been configured. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
