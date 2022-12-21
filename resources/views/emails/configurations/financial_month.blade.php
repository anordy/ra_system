@component('mail::message')
# System Warning,

Financial months {{ $payload['currentMonth'] }} for the year {{ $payload['currentYear'] }} have not been configured. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
