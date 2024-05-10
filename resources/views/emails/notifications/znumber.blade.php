@component('mail::message')
# Hello {{ $business->taxpayer->first_name }},

The ZRA registration for your {{ $business->name }} does not have a previous Z-Number.

To address this, please log in to the system and update your business's Z-Number in the Change Profile option.

If you need additional help, please contact ZRA Support for further information.

Thanks,<br>
{{ config('app.name') }}
@endcomponent