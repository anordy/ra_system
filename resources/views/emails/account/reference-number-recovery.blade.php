@component('mail::message')
# Hello {{ $taxpayer->first_name }},

Please the below reference number to log in into your account.

@component('mail::panel')
Reference No.: {{ $taxpayer->reference_no }}<br>
@endcomponent

If you did not request to recover your reference number, please contact system support.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
