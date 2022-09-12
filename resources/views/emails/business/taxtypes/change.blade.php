@component('mail::message')
# Hello {{ $payload['tax_change']->business->taxpayer->first_name }} {{ $payload['tax_change']->business->taxpayer->last_name }},

According to your tax type change request submission, from {{ $payload['time'] }} you have been changed from: <br>
    
<span>- {{ $payload['tax_change']->fromTax->name }} to {{ $payload['tax_type']->taxType->name }}</span> <br>

Please login into your account to view more details.

@component('mail::button', ['url' => 'https://uat.ubx.co.tz:8888/zrb_client/public/login', 'color' => 'primary'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
