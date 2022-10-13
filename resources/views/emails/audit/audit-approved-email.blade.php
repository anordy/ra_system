@component('mail::message')
# Hello {{ $taxpayerName }},

Your audit for business **{{ $payload[1]->business->name }}** has been completed and approved.
Thanks,<br>
{{ config('app.name') }}
@endcomponent
