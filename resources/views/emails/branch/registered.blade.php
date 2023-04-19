@component('mail::message')
# {{ __('Hello') }} {{ $location->taxpayer->first_name }},

{{ __('Your ZRA business branch registration for') }} {{ $location->name }} {{ __('has been received. You will be notified once approved') }}.

{{ __('This approval process may take up to approximately two (2) working days') }}.

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
