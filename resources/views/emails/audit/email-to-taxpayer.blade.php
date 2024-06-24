@component("mail::message")
    # Hello {{ $taxpayerName }},

    Your Business have been selected to be audited, two weeks before auditing you will receive notification specifying the exact Audit date.

    Thanks,<br>
    {{ config("app.name") }}
@endcomponent
