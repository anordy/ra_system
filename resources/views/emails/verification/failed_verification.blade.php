@component('mail::message')
# System Warning

Data tempering has been detected on record {{ $payload->row_id }} at table {{ $payload->table }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
