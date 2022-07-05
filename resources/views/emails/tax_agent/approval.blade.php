@component('mail::message')
# Hello {{ $fullname }},
@if($status == 1)
Your application as tax agent has been approved successfully
use this control number <strong>99306474554</strong> to pay for the service
@else
    Your application as tax agent has been rejected, please try to edit your application or apply again
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
