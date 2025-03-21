@component('mail::message')
# Hello {{ $closure->business->taxpayer->first_name }},

Your ZRA temporary business closure for {{ $closure->business->name }} @if ($closure->location)
    , {{ $closure->location->name }}
@endif has been approved. 

You are required/obliged to submit NIL return to ZRA within the closure period.

Thanks,<br>
{{ config('app.name') }}
@endcomponent