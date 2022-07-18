@component('mail::message')
# Greetings {{ $full_name }},

You are successful registered as a Withholding Agent for {{ $institution_name }}. Kindly login with your reference number to view the information.<br>


Thanks,<br>
{{ config('app.name') }}
@endcomponent
