@component('mail::message')
# Hello {{ $fullname }},
@if($status == 1)
Your application as tax consultant has been approved successfully and here is your reference number
<strong>{{$reference_number}}</strong>.
    You can use this number for any business concerning consultation.
@else
    Your payment registration for the application as tax consultant has been rejected
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
