@component('mail::message')
    # Hello {{ $business->name ?? 'N/A' }},

    {{ $message }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
