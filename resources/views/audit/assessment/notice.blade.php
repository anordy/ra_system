<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tax Audit Notice Of Assessment</title>
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
            width: 100px;
            height: 100px;
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

        .letterhead {
            height: 80px;
        }
    </style>
</head>

<body style="font-size: 17px !important;">
<div class="align-center">
    <img class="logo" src="{{ public_path() }}/images/logo-square.png" alt="ZRA Logo">
</div>
<table width="100%" class="yellow-bottom">
    <tr>
        <td width="100%"
            style="font-size: 28px; text-transform: uppercase;text-align: center;font-family: sans-serif; letter-spacing: 14px"
            class="bold">Zanzibar Revenue Authority
        </td>
    </tr>
</table>
<table width="100%" class="blue-bottom" style="margin: 5px 0 10px 0">
</table>
<div class="align-center">
    <span style="margin-top: 20px; font-weight: bold; font-size: 22px; display: block">NOTICE OF ASSESSMENT</span>
    <span style="font-size: 20px; display: block">(Issued under section 21 of the Tax Administration and Procedures Act No. 7 of 2009)</span>
</div>
<br>
<div class="center">

    <table width="100%">
        <tr>
            <td>
                <table width="90%" class="table-bordered">
                    <tr>
                        <td>
                            <span>TIN NO.</span>
                        </td>
                        @for ($i = 0; $i <= 8; $i++)
                            <td>{{ $audit->business->tin[$i] ?? "" }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span>DATE.</span>
                        </td>
                        @for ($i = 0; $i <= 7; $i++)
                            <td>{{ \Carbon\Carbon::create($audit->approved_on)->format("dmY")[$i] ?? "" }}</td>
                        @endfor
                    </tr>

                </table>
            </td>
            <td>
                <table width="90%" class="table-bordered">
                    <tr>
                        <td>
                            <span>ZRA NO.</span>
                        </td>
                        @for ($i = 0; $i <= 10; $i++)
                            <td>{{ $audit->business->ztn_number[$i] ?? "" }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>
                            <span>VRN NO.</span>
                        </td>
                        @for ($i = 0; $i <= 10; $i++)
                            <td>{{ $audit->business->vrn[$i] ?? "" }}</td>
                        @endfor
                    </tr>
                    <tr>
                        <td>
                            <span>ASS NO.</span>
                        </td>
                        <td colspan="11">{{ $audit->id }}</td>
                    </tr>

                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="90%" class="table-bordered">
                    <tr>
                        <td colspan="10">
                            <span><strong>TO:</strong> {{ $audit->business->name ?? "N/A" }}</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table width="90%" class="table-bordered">
                    <tr>
                        <td>
                            <span class="bold">ISSUING OFFICE:</span>
                        </td>
                        <td>
                            <span> {{ $audit->business->headquarter->taxRegion->department->name ?? "N/A" }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p>
        An examination of your records and accounts has revealed discrepancies between Tax Liability Declared by you on
        your @foreach ($audit->assessments as $assessment)
            {{ $assessment->taxtype->name }} return,
        @endforeach and the Actual Liability deemed to be due on the
        evidence available.
    </p>

    <p>
        Accordingly, the Commissioner General has under the provision of section 19 of the Tax Administration and Procedures
        Act, No. 7 of 2009 assessed the additional tax payable by you for the said period as being:
    </p>

    <table width="100%" class="table-bordered">
        <tr class="bold">
            <td>PERIOD</td>
            <td>DESCRIPTION</td>
            <td>ACCOUNT LIABILITY IN USD/TZS</td>
            <td>DECLARED LIABILITY IN USD/TZS</td>
            <td>AMOUNT PAYABLE IN USD/TZS</td>
        </tr>

        @foreach ($audit->assessments as $i => $assessment)
            <tr>
                @if ($i === 0)
                    <td rowspan="{{ $audit->assessments->count() }}">{{ \Carbon\Carbon::create($audit->period_from)->format("Y") }}
                        - {{ Carbon\Carbon::create($audit->period_to)->format("Y") }}</td>
                @endif
                <td>Principal {{ $assessment->taxtype->name }} Due</td>
                <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }}
                    {{ number_format($assessment->principal_amount, 2) }}</td>
                <td>-</td>
                <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }}
                    {{ number_format($assessment->principal_amount, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td rowspan="2"></td>
            <td>Penalty (Sec 31)</td>
            <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalPenalty, 2) }}</td>
            <td>-</td>
            <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalPenalty, 2) }}</td>
        </tr>
        <tr>
            <td>Interest (Sec 31)</td>
            <td> {{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalInterest, 2) }}</td>
            <td>-</td>
            <td> {{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalInterest, 2) }}</td>
        </tr>

        <tr class="bold">
            <td>TOTAL DUE</td>
            <td>-</td>
            <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalAmount, 2) }}</td>
            <td>-</td>
            <td>{{ $assessment->currency == \App\Models\Currency::TZS ? "TZS" : "USD" }} {{ number_format($totalAmount, 2) }}</td>
        </tr>

    </table>

    <p>
        Detailed of calculation of the tax are attached to this notice.
    </p>

    <p>
        You should make payment within thirty days of this assessment.
    </p>

    <p>
        In case you are aggrieved by this assessment you may object by filing an objection in writing to the
        Commissioner General within thirty days from the date of service of this assessment, in accordance with the
        provisions of section 21A of the Tax Administration and Procedures No. 7 of 2009 and its Regulations.
    </p>

    <p>
        Payment of the amount owing should be made before or on due date, failure of which recovery proceedings will be
        instituted upon you without further notice.
    </p>

</div>
<div class="align-center" style="background-color: red; position:fixed; bottom: 0">
    <img class="letterhead" src="{{ public_path() . "/images/letterhead.png" }}">
</div>
</body>

</html>
