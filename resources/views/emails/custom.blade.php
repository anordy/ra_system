@component('mail::message')
# Greetings {{ $payload['name'] }},

{{ $payload['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
