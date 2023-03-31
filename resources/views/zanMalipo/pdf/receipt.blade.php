<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Government Bill</title>
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
            margin-bottom: 8px;
        }

        .padding-left {
            padding-left: 10px;
        }

        .td-title {
            width: 30%;
        }

        .td-content {
            width: 70%;
        }

        .border_bottom {
            border-bottom: 1px solid black;
        }

        .border_top {
            border-top: 1px solid black;
        }
    </style>
</head>

<body style="font-size: 12px !important;">
    <div class="top align-center">
        <img src="{{ public_path() }}/images/logo.png" alt="ZRA Logo">
        <span style="margin-top: 8px; display: block">Zanzibar</span>
        <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">ZANZIBAR REVENUE AUTHORITY
        </span>
        <span style="margin-bottom: 8px;margin-top: 8px; display: block">Government Receipt</span>
    </div>
    <br>
    <div class="center">
        <table width="100%">
            {{-- section 1 --}}
            <tr>
                <td colspan="2" class="padding-left td-title">Control Number</td>
                <td colspan="2" class="td-content">: <b>{{ $bill->control_number }}</b></td>
                <td colspan="2" rowspan="6">
                    {{-- <img src="{{ $dataUri }}" alt="" style="width: 150px; height: 150px"> --}}
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Payment Ref</td>
                <td colspan="2" class="td-content">:
                    @if ($bill->bill_payments->count() > 0)
                        <small>
                            {{ $bill->bill_payments->first()->pay_ref_id }}
                        </small>
                        
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Bill Reference</td>
                <td colspan="2" class="td-content">: {{ $bill->id }} </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Receipt No</td>
                <td colspan="2" class="td-content">:
                    @if ($bill->bill_payments->count() > 0)
                        {{ $bill->bill_payments->first()->psp_receipt_number }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Service Provider Code</td>
                <td colspan="2" class="td-content">: {{ config('modulesconfig.sp_code') }} </td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Payer Name</td>
                <td colspan="2" class="td-content">:
                    {{ $bill->payer_name ?? '' }}</td>
            </tr>
            <tr>
                <td colspan="2" class="padding-left td-title">Payer Phone</td>
                <td colspan="2" class="td-content">: {{ $bill->payer_phone_number ?? '' }} </td>
            </tr>
            <tr></tr>
            <tr></tr>
        </table>
        <br>
        <table width="100%">
            <tr>
                <td colspan="2" class="padding-left td-title">Bill Description</td>
                <td colspan="2" class="td-content">: {{ $bill->description ?? '' }} </td>
            </tr>

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
                <td colspan="3" class="align-right bold">
                    {{ number_format($bill->amount, 2) }} {{ $bill->currency }}
                </td>
            </tr>
        </table>
        <br>

        <table width="100%">
            <tr>
                <td>Bill reference</td>
                <td colspan="3" class="bold">:
                    {{ $bill->id }}</td>
            </tr>
            <tr>
                <td>Amount in Words</td>
                <td colspan="3" class="bold">:
                    @php
                        $f = convertMoneyToWord($bill->amount);
                        echo '' . ucwords($f) . ' ' . ($bill->currency == 'TZS' ? 'Tanzanian Shilling' : $bill->currency) . ' Only.';
                    @endphp
                </td>
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
                <td>Issued By</td>
                <td colspan="3">: {{ $bill->payer_name }}</td>
            </tr>
            <tr>
                <td>Issued On</td>
                <td colspan="3">: {{ date('d-M-Y', strtotime(date('Y-m-d H:i:s'))) }}</td>
            </tr>
            <tr>
                <td>Signature</td>
                <td colspan="3">...................</td>
            </tr>
        </table>
        <br>
    </div>
    <div class="bottom align-center">
        <p> Zanzibar Revenue Authority &copy; {{ date('Y') }} All Rights Reserved (ZRA)</p>
    </div>
</body>

</html>
