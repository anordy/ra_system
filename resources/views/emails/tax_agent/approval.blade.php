@component('mail::message')
# Hello {{ $fullname }},
@if($status === 'approved')
Your application as tax consultant has been approved successfully and here is your reference number
<strong>{{$reference_number}}</strong>.
    You can use this number for any business concerning consultation.
@elseif($status === 'rejected')
    Your payment registration for the application as tax consultant has been rejected
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
