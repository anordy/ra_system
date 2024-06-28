@component("mail::message")
    # Hello {{ $taxpayerName }},

    We hope this message finds you well.

    We are writing to inform you that your business, **{{ $payload[1]->business->name }}**, has been selected for a tax audit. The audit is scheduled to
    take place on **{{ $payload[1]->auditing_date }}**. It is crucial that you cooperate fully with our audit team during this process.

    As part of the audit procedure, we kindly request that you log in to our system and upload the documents outlined in the attached notification letter.

    Additionally, we want to inform you that if you require an extension for the audit process, you can request it through our system when you log in.

    Please ensure that all requested documents are uploaded by
    **{{ \Carbon\Carbon::parse($payload[1]->notification_letter_date)->addDays(7)->format("d-m-Y") }}** to facilitate a smooth and efficient audit
    process.

    Thanks,<br>
    {{ config("app.name") }}
@endcomponent
