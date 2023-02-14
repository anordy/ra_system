@component('mail::message')
# Hello {{$payload['taxpayerName']}},

{{ $payload['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
