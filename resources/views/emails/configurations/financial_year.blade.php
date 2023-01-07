@component('mail::message')
# System Warning,

Financial year {{ $payload['currentYear'] }} has not been configured. Please log into the system and perform configurations.

Be advised to configure penalty rates, interest rates and financial months for the missing financial year.

System will not function without configuring the rate.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
