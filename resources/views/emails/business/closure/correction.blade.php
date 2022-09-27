@component('mail::message')
# Hello {{ $closure->business->taxpayer->first_name }},

Your ZRB temporary business closure for {{ $closure->business->name }} @if ($closure->location)
    , {{ $closure->location->name }}
@endif requires corrections. 

Login into your account to for more details.

Thanks,<br>
{{ config('app.name') }}
@endcomponent