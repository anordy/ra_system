@component('mail::message')
    # Hello {{ $taxpayer->first_name }},

    {{ $message }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
