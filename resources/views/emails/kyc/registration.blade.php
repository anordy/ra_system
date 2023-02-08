@component('mail::message')
# {{ __('Hello') }} {{ $kyc->first_name }},

{{ __('Your application has been accepted, please visit ZRA office for biometric registration (fingerprint) before') }} **{{ $kyc->created_at->addMonth()->toFormattedDateString() }}** .

{{ __('Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
