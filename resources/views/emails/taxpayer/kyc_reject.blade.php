@component('mail::message')
# Hello {{ $kyc->first_name }},

Your application for ZRA reference number has been rejected. Please find the comments below and re-apply again.

Application Details
@component('mail::table')
|    |  |
| ------------- |:-------------:| --------:|
| Name   | {{ $kyc->first_name }} {{ $kyc->middle_name }} {{ $kyc->last_name }} |
| Email | {{ $kyc->email ?? 'N/A' }} |
| Mobile | {{ $kyc->mobile }} |
| Alt Mobile | {{ $kyc->alt_mobile ?? 'N/A' }} |
| ID Type | {{ $kyc->identification->name ?? 'N/A' }} |
@if ($kyc->identification->name === \App\Models\IDType::NIDA)
| NIDA | {{ $kyc->nida_no ?? 'N/A' }} |
@elseif($kyc->identification->name === \App\Models\IDType::ZANID)
| ZANID | {{ $kyc->zanid_no ?? 'N/A' }} |
@elseif($kyc->identification->name === \App\Models\IDType::PASSPORT)
| PASSPORT NO | {{ $kyc->passport_no ?? 'N/A' }} |
| PERMIT NO | {{ $kyc->permit_number ?? 'N/A' }} |
@endif
| Physical Address | {{ $kyc->physical_address }} |
| Street | {{ $kyc->street }} |
@endcomponent

@component('mail::panel')
Comments: **{{ $kyc->comments }}**
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
