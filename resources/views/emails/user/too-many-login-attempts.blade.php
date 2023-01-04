@component('mail::message')
    # Hello,

    {{ $payload['message'] }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent