@component('mail::message')
    # Hello {{ $taxpayer_name }},

    {{ $message }}

    Please login to make necessary corrections.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
