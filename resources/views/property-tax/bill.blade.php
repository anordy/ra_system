<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Property Tax Bill</title>
    <style>
        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        img.logo {
            width: 140px;
            height: 140px;
        }

        img.signature {
            width: 100px;
            height: 100px;
        }

        .dashed-bottom {
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

        .padding-left {
            padding-left: 10px;
        }

        .td-title {
            width: 50%;
        }

        .td-content {
            width: 50%;
        }

        .table-bordered td {
            border: solid 1px rgba(0, 0, 0, 0.36);
        }

        td {
            padding: 5px;
        }

    </style>
</head>

<body style="font-size: 16px !important;">
<div class="align-center">
    <img class="logo" src="{{ public_path() }}/images/logo.png" alt="ZRA Logo">
</div>
<table width="100%" class="dashed-bottom">
    <tr>
        <td width="50%" style="font-size: 22px" class="bold">Zanzibar Revenue Authority</td>
        <td width="50%" style="font-size: 22px" class="bold align-right">Mamlaka ya Mapato Zanzibar</td>
    </tr>
</table>
<div class="align-center">
  <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">P.O. BOX 2072</span>
    <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">Tel: 255242230639, Fax: 255242233904</span>
    <span style="margin-top: 8px; font-weight: bold; font-size: 18px; display: block">Email: zra@zanrevenue.org</span>
    <span style="margin-top: 20px; font-weight: bold; font-size: 22px; display: block">Property Tax Bill</span>
</div>
<br>
<div class="center">
    <table width="100%" class="table-bordered">
        <tr>
            <td colspan="2" class="padding-left td-title bold">Taxpayer's Name/Jina la Mlipakodi</td>
            <td colspan="2" class="td-content"><b>{{ $propertyPayment->property->taxpayer->fullname() }}</b></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Taxpayer's Number/Namba ya Mlipakodi</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->taxpayer->reference_no }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Address/Anwani</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->taxpayer->physical_address }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Bill No</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->id }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Date issued/Tarehe</td>
            <td colspan="2" class="td-content">
                {{ \Carbon\Carbon::create($propertyPayment->created_at)->format('d M Y H:i:s') }}</td>
        </tr>
        <tr>
            <td colspan="4" class="padding-left td-title bold" style="vertical-align: top" height="20">Details of Property / Maelezo ya Jengo</td>
        </tr>
        <tr>
            <td class="padding-left td-title bold">BRN</td>
            <td class="td-content">{{ $propertyPayment->property->urn }}</td>
            <td class="padding-left td-title bold">District / Wilaya</td>
            <td class="td-content">{{ $propertyPayment->property->district->name }}</td>
        </tr>
        <tr>
            <td class="padding-left td-title bold">Plot No / Namba ya Nyumba</td>
            <td class="td-content">{{ $propertyPayment->property->name ?? $propertyPayment->property->house_number }}</td>
            <td class="padding-left td-title bold">Street / Mtaa</td>
            <td class="td-content">{{ $propertyPayment->property->street->name }}</td>
        </tr>
        <tr>
            <td class="padding-left td-title bold">Use / Matumizi</td>
            <td class="td-content">{{ formatEnum($propertyPayment->property->usage_type) }}</td>
            <td class="padding-left td-title bold">Block No.</td>
            <td class="td-content">{{ $propertyPayment->property->name ?? $propertyPayment->property->house_number }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Payment Control No/Nambari ya Malipo</td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->latestBill->control_number }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold" height="10"></td>
            <td colspan="2" class="td-content"></td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Being Payment of Annual Property Tax for the Property
                located
                at
            </td>
            <td colspan="2" class="td-content"> {{ $propertyPayment->property->street->name }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Tax Rate/Kiwango</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Property Tax Arrears/Malimbikizo ya Kodi</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="padding-left td-title bold">Total/Jumla</td>
            <td colspan="2" class="td-content"> {{ number_format($propertyPayment->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" class="padding-left td-title bold">NOTE:</td>
        </tr>
        <tr>
            <td colspan="4" class="padding-left td-title">All payments (in Tanzania Shillings) should be addressed to
                the Commissioner General Zanzibar Revenue Authority.
            </td>
        </tr>
        <tr>
            <td colspan="4" class="padding-left">Failure to make the payment will attract an interest as prescribed
                under Section 33 of the Tax Administration and Procedures Act No. 7 of 2009.
            </td>
        </tr>
        <tr>
            <td colspan="4" class="padding-left td-title">- Please make sure the payment is done under the name
                of {{ $propertyPayment->property->taxpayer->fullname() }}</td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 20px">
        <tr>
            <td class="td-content align-center">
                <img class="signature" src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
            </td>
        </tr>
        <tr>
            <td class="td-content align-center"><b>{{$commissinerFullName}}</b></td>
        </tr>
        <tr>
            <td class="td-content align-center"><b>Commissioner General</b></td>
        </tr>
        <tr>
            <td class="td-content align-center" height="10"></td>
        </tr>
        <tr>
            <td class="td-content align-center"><i>"Lipa Kodi kwa Maendeleo ya Zanzibar"</i></td>
        </tr>
    </table>
</div>
<div class="bottom align-center">
    <p> Zanzibar Revenue Authority &copy; {{ date('Y') }} All Rights Reserved (ZRA)</p>
</div>
</body>

</html>
