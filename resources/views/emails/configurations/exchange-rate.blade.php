@component('mail::message')
# System Warning

{{ $payload['currency'] }} Exchange rate for month {{ $payload['date'] }} have not been configured. Please log into the system and perform configurations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
