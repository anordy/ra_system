@component('mail::message')
# System Warning,

Financial month {{ $payload['currentMonth'] }} for the year {{ $payload['currentYear'] }} has not been configured.

Be advised to configure Financial Month for Seven Day Returns.

System will not function without configuring the financial month. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
