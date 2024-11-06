@component('mail::message')
    # Business De-registration

    This mail is to notify you that {{ $businessName }}'s de-registration request has been {{ $status }}.

    @if($status === \App\Models\BusinessStatus::REJECTED)
        Please clear all your outstanding liabilities to perform business de-registration.
    @endif

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
