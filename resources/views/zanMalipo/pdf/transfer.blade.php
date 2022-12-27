<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Electronic Funds Transfer From</title>
    <style>
        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .top img {
            width: 50px;
            height: 50px;
        }

        .top {
            border-bottom: 1px dashed black;
        }

        table {
            border-collapse: collapse;
        }

        tr.border-bottom td {
            border-bottom: 1px dotted black;
        }

        tr.border-top td {
            border-top: 1px dotted black;
        }

        @page {
            size: 7in 9.25in;
            margin: 10mm 15mm 10mm 15mm;
        }

        .bold {
            font-weight: bold;
        }

        .block {
            display: block;
        }

        .margin-top-bottom {
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .margin-top {
            margin-top: 8px;
        }

        .margin-bottom {
            margin-bottom: 6px;
        }

        .border_bottom {
            border-bottom: 1px dashed black;
        }

        .padding-left {
            padding-left: 10px;
        }

        .td-title {
            width: 40%;
        }

        .td-content {
            width: 60%;
        }
    </style>
</head>

<body style="font-size: 12px !important;">
    <div class="top align-center">
        <img src="{{ public_path() }}/images/logo.png" alt="ZRB Logo">
        <span style="margin-top: 8px; display: block">Zanzibar</span>
        <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">ZANZIBAR REVENUE BOARD
        </span> REVENUE BOARD</span>
        <span style="display: block; margin-top: 4px; margin-bottom: 4px;">
            Order From for Electronic Funds Transfer to {{$bankAccount->bank->name}}
        </span>
    </div>
    <br>
    <div>
        <table width="100%">
            <caption style="font-weight: bold; text-align: left">(a) Remitter/Tax Payer Details :-</caption>
            <tr>
                <td colspan="3" class="padding-left td-title">Name of Account Holder(s)</td>
                <td colspan="3" class="td-content">: <span
                        style="border-bottom: 1px dashed black; width: 100%; display: block"></span></td>
            </tr>
            <tr>
                <td colspan="3" class="padding-left td-title">Name of Commercial Bank</td>
                <td colspan="3" class="td-content">: <span
                        style="border-bottom: 1px dashed black; width: 100%; display: block"></span></td>
            </tr>
            <tr>
                <td colspan="3" class="padding-left td-title">Bank Account Number</td>
                <td colspan="3" class="td-content">: <span
                        style="border-bottom: 1px dashed black; width: 100%; display: block"></span></td>
            </tr>
            <tr>
                <td colspan="3" class="padding-left td-title">Signatories</td>
                <td colspan="1"><span class="border_bottom block" style="margin-top: 10px; width: 95%"></span></td>
                <td colspan="1"><span class="" style="margin-top: 10px; width: 100%"> | </span></td>
                <td colspan="1"><span class="border_bottom block" style="margin-top: 10px; width: 100%"></span></td>
            </tr>
            <tr>
                <td colspan="3" class="padding-left td-title"></td>
                <td colspan="1" style="font-size: 12px">Signature of the Transfer One</td>
                <td colspan="1" style="font-size: 12px"></td>
                <td colspan="1" style="font-size: 12px">Signature of the Transfer Two</td>
            </tr>
        </table>

        <table width="100%">
            <caption style="font-weight: bold; text-align: left">(b) Beneficiary Details :-</caption>
            <tr>
                <td colspan="2" class="padding-left td-title"></td>
                <td colspan="2" class="td-content bold">: {{ $bankAccount->account_name }}</td>
                <td colspan="2" rowspan="7">
                    <img src="{{ $dataUri }}" alt="" style="width: 150px; height: 150px">
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title"></td>
                <td colspan="2" class="td-content bold">: {{ $bankAccount->bank->name }}</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Bank Account Number</td>
                <td colspan="2" class="td-content bold">: {{ $bankAccount->account_number }} </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">SWIFT Code</td>
                <td colspan="2" class="td-content bold">: {{ $bankAccount->swift_code }} </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Payment Control Number</td>
                <td colspan="2" class="td-content bold">: {{ $bill->control_number ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Payer Name</td>
                <td colspan="2" class="td-content">:
                    {{ $bill->payer_name ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title"><small>Beneficiary Account (Field 59 of MT103)</small>
                </td>
                <td colspan="2" class="td-content bold">: /{{ $bankAccount->account_number }} </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title"><small>Payment Reference (Field 70 of MT103)</small>
                </td>
                <td colspan="2" class="td-content bold">: /ROC/{{ $bill->control_number ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Transfer Amount</td>
                <td colspan="2" class="td-content bold">: {{ number_format($bill->amount, 2) }} (TZS)</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Amount in Words</td>
                <td colspan="4" class="td-content bold">:
                    @php
                        $f = convertMoneyToWord($bill->amount);
                        echo '' . ucwords($f) . ' ' . ($bill->currency == 'TZS' ? 'Tanzanian Shilling' : $bill->currency) . ' Only.';
                    @endphp
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Being payment for</td>
                <td colspan="4" class="td-content">: {{ $bill->description ?? '' }}</td>
            </tr>
        </table>
        
        <table width="100%">
            @foreach ($bill->bill_items as $billItem)
                <tr class="border-top">
                    <td colspan="2" class="padding-left td-title">Billed Item ({{ $loop->index + 1 }}) </td>
                    <td colspan="2">: {{ $billItem->taxType->name }}

                    </td>
                    <td colspan="2" class="align-right">
                        {{ number_format($billItem->amount, 2) ?? '' }}
                    </td>
                </tr>
            @endforeach
            <tr class="border-top">
                <td colspan="3" class="align-right bold"> Total Billed Amount </td>
                <td colspan="4" class="align-right bold">
                    {{ number_format($bill->amount, 2) }} {{ $bill->currency }}
                </td>
            </tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td>Expires On</td>
                <td colspan="3">: {{ \Carbon\Carbon::create($bill->expire_date)->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td>Prepared By</td>
                <td colspan="3" class="bold">:
                    {{ $bill->payer_name }}</td>
            </tr>
            <tr>
                <td>Collection Centre</td>
                <td colspan="3">: HEAD QUARTER</td>
            </tr>
            <tr>
                <td>Printed By</td>
                <td colspan="3">: {{ $bill->payer_name }}</td>
            </tr>
            <tr>
                <td>Printed On</td>
                <td colspan="3">: {{ date('d-M-Y', strtotime(date('Y-m-d H:i:s'))) }}</td>
            </tr>
            <tr>
                <td>Signature</td>
                <td colspan="3">...................</td>
            </tr>
        </table>
        <table width="100%" style="font-size: 8.5px">
            <tr>
                <td><b>Note to Commercial Back: </b></td>
            </tr>
            <tr>
                <td>
                    <ol>
                        <li>
                            Please capture the above information correctly.
                            Do not change or add any text, symbols or digits on the information provided.
                        </li>
                        <li>
                            Field 59 of MT103 is an <b>"Account Number"</b>
                            with value: <b>/{{$bankAccount->account_number}} </b>. Must be captured correctly.
                        </li>
                        <li>
                            Field 70 of MT103 is a <b>"Control Number"</b>
                            with value: <b>/ROC/{{ $bill->control_number ?? '' }}</b>. Must be captured correctly.
                        </li>
                    </ol>
                </td>
            </tr>
        </table>
    </div>
    <div class="bottom align-center" style="font-size: 6px">
        <p> Zanzibar Revenue Board &copy; {{ date('Y') }} All Rights Reserved (ZRB)</p>
    </div>
</body>

</html>