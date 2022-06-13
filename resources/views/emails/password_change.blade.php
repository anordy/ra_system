@component('mail::message')
# Greetings,

This is a confirmation that the password for your {{ config('app.name') }} has just been changed.<br>
If you didn't change your password, please refer to {{ config('app.name') }} Help Center.<br>

@component('mail::button', ['url' => $url, 'color' => 'success'])
Click here to Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
