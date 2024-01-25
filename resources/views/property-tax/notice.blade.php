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
            height: 120px;
            margin-bottom: 10px;
        }

        img.signature {
            width: 80px;
            height: 80px;
        }

        .yellow-bottom {
            border-bottom: 4px solid #e7e149;
        }

        .blue-bottom {
            border-bottom: 4px solid #467fbc;
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
        p {
            font-size: 19px;
        }
        .td-content {
            width: 50%;
            font-size: 19px;
        }

        .table-bordered td {
            border: solid 1px rgba(0, 0, 0, 0.36);
        }

        td {
            padding: 5px;
        }

        .letterhead {
            height: 80px;
        }

        ol li {
            text-align: justify;
            font-size: 19px;
            line-height: 1.5;
        }

    </style>
</head>

<body style="font-size: 16px !important;">
<div class="align-center">
    <img class="logo" src="{{ public_path() }}/images/logo-square.png" alt="ZRA Logo">
</div>
<table width="100%" class="yellow-bottom">
    <tr>
        <td width="100%" style="font-size: 28px; text-transform: uppercase;text-align: center;font-family: sans-serif; letter-spacing: 14px" class="bold">Zanzibar Revenue Authority</td>
    </tr>
</table>
<table width="100%" class="blue-bottom" style="margin: 5px 0 20px 0">
</table>
<table width="100%">
    <tr>
        <td width="50%" style="font-size: 19px; text-transform: uppercase; padding: 0">Ref . No. <b>{{ $propertyPayment->property->taxpayer->reference_no }}</b></td>
        <td width="50%" style="font-size: 19px; padding: 0" class=" align-right">
            <b>{{ $propertyPayment->created_at->toFormattedDayDateString() }}</b>
        </td>
    </tr>
</table>
<div class="" style="margin-bottom: 20px">
    <span style="margin-top: 8px; text-transform: uppercase; font-size: 19px; display: block">{{ $propertyPayment->property->taxpayer->fullname() }}</span>
    <span style="margin-top: 8px; text-transform: uppercase; font-size: 19px; display: block">{{ $propertyPayment->property->taxpayer->physical_address }}</span>
    <span style="margin-top: 8px; text-transform: uppercase; font-size: 19px; display: block">ZANZIBAR.</span>
</div>
<div class="align-center">
    <span style="margin-top: 8px; font-weight: bold; font-size: 19px; display: block; padding: 0 70px">
        RE: NOTICE OF REGISTRATION AND ASSESSMENT OF PROPERTY TAX AS PROVIDED UNDER PROPERTY TAX ACT, NO.14 OF 2008.
    </span>
</div>
<div>
    <ol>
        <li>
            The approved staff of Zanzibar Revenue Authority (ZRA), have visited your property with number <b>{{ $propertyPayment->property->urn }}</b>, located at of <b>{{ $propertyPayment->property->ward_id }}</b>, <b>{{ $propertyPayment->property->district_id }}</b>, and thereby confirmed that <b>{{ $propertyPayment->property->taxpayer->fullname() }}</b> is the OWNER of that property which is a
            {{ formatEnum($propertyPayment->property->type) }} {{ $propertyPayment->property->storeys->count() ? $propertyPayment->property->storeys->count() . ", storey building." : "." }}
        </li>
        <li>
            In view of the aforesaid and as provided under section 4 of the Property Tax Act, No. 14 of 2008 (herein referred as the Act),  <b>{{ $propertyPayment->property->taxpayer->fullname() }}</b> is hereby registered as Taxpayer of Property Tax with registration number <b>{{ $propertyPayment->property->urn }}</b>
        </li>
        <li>
            As a result of that, and by virtue of section 7  of the Act, read together with the issued Government Order number 78, of 2023, you are hereby assessed to pay annual property tax of  TZS. <b>{{ number_format($propertyPayment->total_amount, 2) }}</b> for the period of {{ $propertyPayment->year->name }}.
        </li>
        <li>
            According to section 10 of the Act, you are reminded to pay the annual property tax amount of TZS. <b>{{ number_format($propertyPayment->total_amount, 2) }}</b> not later than 30th November 2023, through issued control number <b>{{ $propertyPayment->latestBill->control_number }}</b> at nearby PBZ branch/Agent, any other bank or via Mobile Network Operator (TIGO/ZANTEL) by dialing *150*01#, Select No 4, then select No. 8 (Government Payment) and place issued Control Number.
        </li>
        <li>
            Failure to pay such sum within specified time is an offence and shall attract interest as provided under section 33 of the Tax Administrative and Procedure Act, No 7 of 2009.
        </li>
    </ol>
</div>
<div>
    <p style="font-size: 19px;">
        If you are aggrieved by this decision, you have right to object on the grounds of merit within five days from the date of receipt of this notice as provided by the law.
    </p>
    <p style="font-size: 19px;">
        Kindly be guided.
    </p>
    <table width="100%" style="margin-top: 20px">
        <tr>
            <td class="td-content align-center"><b>"Tulipe Kodi kwa Maendeleo ya Zanzibar"</b></td>
        </tr>
        <tr>
            <td class="td-content align-center" height="2"></td>
        </tr>
        <tr>
            <td class="td-content align-center">
                <img class="signature" src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
            </td>
        </tr>
        <tr>
            <td class="td-content align-center" style="text-transform: capitalize;"><b>{{strtolower($commissinerFullName)}}</b></td>
        </tr>
        <tr>
            <td class="td-content align-center"><b>Commissioner General</b></td>
        </tr>

    </table>
</div>
<div class="align-center" style="background-color: red; position:fixed; bottom: 0">
    <img class="letterhead" src="{{ public_path() . '/images/letterhead.png'  }}">
</div>
</body>
</html>
