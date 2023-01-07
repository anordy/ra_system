@component('mail::message')
    # Hello,

    {{ $payload['message'] }}

    Thanks,
    {{ config('app.name') }}
@endcomponent