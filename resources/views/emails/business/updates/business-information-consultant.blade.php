@component('mail::message')
# Hello {{ $payload['consultant']->taxpayer->first_name }} {{ $payload['consultant']->taxpayer->last_name }},

You have been approved to be a tax consultant for {{ $payload['business']->name }} business.

Please login into your account to view more details.

@component('mail::button', ['url' => 'https://portalcrdb.zanrevenue.org/', 'color' => 'primary'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
