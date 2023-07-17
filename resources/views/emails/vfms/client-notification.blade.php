@component('mail::message')
    # Hello {{ $user_name }},

    {{ $message }}

    @if($user_type == 'taxpayer')
        Please login to make necessary corrections.
    @endif
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
