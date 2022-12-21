@component('mail::message')
# System Warning,

Financial year {{ $payload['currentYear'] }} have not been configured. Please log into the system and perform configurations.

Be advised to configure penalty rates, interest rates and financial months for the missing financial year.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
