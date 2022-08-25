@component('mail::message')
# Hello {{ $payload['debt']->business->taxpayer->first_name }} {{ $payload['debt']->business->taxpayer->last_name }},

This is your @if ($payload['debt']->demand_notice_count == 1) first @elseif ($payload['debt']->demand_notice_count == 2) second @elseif ($payload['debt']->demand_notice_count == 3) final @endif debt demand notice for {{ $payload['debt']->business->name }}. You must pay your debt within seven (7) days.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
