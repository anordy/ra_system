@component('mail::message')
# Hello {{ $payload['business']->taxpayer->first_name }} {{ $payload['business']->taxpayer->last_name }},

According to your tax type change request submission, from {{ $payload['time'] }} you have been changed from: <br>
    
@foreach ($payload['new_taxes'] as $tax)
<span>- {{ $tax['old'] }} to {{ $tax['new'] }}</span> <br>
@endforeach

Please login into your account to view more details.

@component('mail::button', ['url' => 'https://uat.ubx.co.tz:8888/zrb_client/public/login', 'color' => 'primary'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
