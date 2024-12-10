@component('mail::message')
    # Business Information Comparison

    This mail is to notify you that {{ $businessName }}'s business information has been changed. You can login to view more details.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
