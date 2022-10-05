@component('mail::message')
# Hello {{ $taxpayerName }},

Your have been selected to be audited, two weeks before auditing you will be notified and specified the exact date.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
