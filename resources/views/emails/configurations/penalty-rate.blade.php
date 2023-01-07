@component('mail::message')
# System Warning,

Penalty rates for the year {{ $payload['currentYear'] }} have not been configured. 

System will not function without configuring the rate.

Please log into the system and to perform configuration.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
