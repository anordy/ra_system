@component('mail::message')
    # Hello {{$payload['userName']}},
    
    {{ $payload['message'] }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
