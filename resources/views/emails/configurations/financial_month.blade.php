@component('mail::message')
# System Warning,

Financial month {{ $payload['currentMonth'] }} for the year {{ $payload['currentYear'] }} has not been configured.

System will not function without configuring the rate. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
