@component("mail::message")
    # Hello {{ $taxpayerName }},

    We hope this message finds you well.

    We are writing to inform you that your business, **{{ $payload[1]->business->name }}**, has been selected for a tax verification for return of {{ $return->financialMonth->name ?? '' }}, {{ $return->financialMonth->year->code }}.

    Thanks,<br>
    {{ config("app.name") }}
@endcomponent
