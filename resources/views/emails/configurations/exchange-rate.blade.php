@component('mail::message')
# System Warning

{{ $payload['currency'] }} Exchange rate for month {{ $payload['date'] }} has not been configured. System will not function without configuring the rate. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
