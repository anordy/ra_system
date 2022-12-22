@component('mail::message')
# System Warning,

{{ $payload['currency'] }} Exchange rates for the day {{ $payload['date'] }} have not been configured. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
