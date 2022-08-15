@component('mail::message')
    # Hello {{ $payload['business']->taxpayer->first_name }} {{ $payload['business']->taxpayer->last_name }},

    According to your tax type request submission, from {{ $payload['time'] }} you have been changed from {{ $payload['old_taxtypes'] }} to {{ $payload['new_taxtypes'] }} respectively.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
