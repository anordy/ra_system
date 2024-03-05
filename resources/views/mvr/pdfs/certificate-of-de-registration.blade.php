<!DOCTYPE html>
<html>

<head>
    <title>Certificate of De-registration - {{ $deregistration->registration->chassis->chassis_number }}</title>
    <style>
        body {
            padding: 16px;
            font-family: sans-serif;
        }

        .logo {
            text-align: center;
            display: inline;
        }

        .logo img {
            height: 150px;
        }

        .text-center {
            text-align: center;
        }

        .title1 {
            font-weight: bold;
            padding: 14px;
        }

        .mv-details-wrapper {
            width: 100%;
            margin-top: 20px;
        }

        .mv-details-wrapper > div {
            display: inline-grid;
            width: 49.5%;
        }

        table.owner-details td div {
            padding: 20px 10px;
            font-weight: bold;
            font-size: 22px;
            width: 100%;
        }

        table.owner-details td {
            vertical-align: top;
            width: 290px;
            padding: 8px;
        }

        table.owner-details td:nth-child(2), table.owner-details td:nth-child(4) {
            font-weight: bold;
            width: 240px;
        }

        td span {
            display: inline-block;
        }

        .clearfix {
            overflow: auto;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .mv-details-wrapper tr td:first-child {
            font-weight: bold;
            text-align: right;
            font-size: 22px;
        }

        .mv-details-wrapper tr td:first-child {
            padding: 8px;
        }

        .qr-code {
            overflow: hidden;
            position:absolute;
            top: 70%;
            right: 1%;
            background: white;
            border-radius: 5px;
            height: 180px;
            width: 180px;
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="title1" style="float: left">
        <div class="logo" style="float: left">
            <img src="{{public_path()}}/images/logo.png">
        </div>
        <div style="display: inline; margin-left: 170px;">
            Zanzibar Revenue Authority
        </div>
    </div>
    <div style="float: right">
        <div class="title1">
            Mamlaka ya Mapato Zanzibar
        </div>
        <div style="text-align: right; padding-right: 16px">
            Date: {{\Carbon\Carbon::parse($deregistration->deregistered_at)->format('d M, Y')}}
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="text-center title1">VEHICLE DEREGISTRATION CERTIFICATE</div>
<div class="text-center title1">(made under Section 47 and Regulation 25)</div>

<br/>
<table class="owner-details">
    <tr>
        <td>Vehicle Owner</td>
        <td colspan="3">{{strtoupper($deregistration->taxpayer->fullname())}}</td>
    </tr>
    <tr>
        <td>Registration Date</td>
        <td> {{\Carbon\Carbon::parse($deregistration->registration->registered_at)->format('d M, Y')}}</td>
        <td>Registration Number:</td>
        <td>{{$deregistration->registration->registration_number}}</td>
    </tr>
    <tr>
        <td>Current Plate Number</td>
        <td colspan="3">{{$deregistration->registration->plate_number}}</td>

    </tr>
    <tr>
        <td>Postal Address (if known)</td>
        <td colspan="3">{{ $deregistration->taxpayer->address ?? 'N/A'  }}</td>
    </tr>
</table>

<br>
<br>
<span style="border-bottom: 1px solid black; padding:0 8px;">VEHICLE DETAILS</span>
<div class="mv-details-wrapper">
    <table>
        <tr>
            <td>MANUFACTURER:</td>
            <td>{{strtoupper($deregistration->registration->chassis->make ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>MODEL:</td>
            <td>{{strtoupper($deregistration->registration->chassis->model_type ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>STYLE:</td>
            <td>{{strtoupper($deregistration->registration->chassis->body_type ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>CLASSES:</td>
            <td>{{strtoupper($deregistration->registration->class->name ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>COLOR:</td>
            <td>{{strtoupper($deregistration->registration->chassis->color->name ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>MANUFACTURE DATE:</td>
            <td>{{strtoupper($deregistration->registration->chassis->year ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>CHASSIS NUMBER:</td>
            <td>{{strtoupper($deregistration->registration->chassis->chassis_number)}}</td>
        </tr>
        <tr>
            <td>ENGINE NUMBER:</td>
            <td>{{strtoupper($deregistration->registration->chassis->engine_number)}}</td>
        </tr>
        <tr>
            <td>GROSS WEIGHT:</td>
            <td>{{strtoupper($deregistration->registration->chassis->gross_weight)}}</td>
        </tr>
        <tr>
            <td>SEATING CAPACITY:</td>
            <td>{{strtoupper($deregistration->registration->chassis->passenger_capacity ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>COUNTRY OF ORIGIN:</td>
            <td>{{strtoupper($deregistration->registration->chassis->imported_from ?? 'N/A')}}</td>
        </tr>
        <tr>
            <td>PREVIOUS REG NO:</td>
            <td>N/A</td>
        </tr>
    </table>
</div>
<br>
The vehicle described above has been {{$deregistration->reason->name ?? 'N/A'}}
<br>
<br>
<br>
<br>

<hr>
<div style="text-align: justify">
    I <strong>{{strtoupper($deregistration->taxpayer->fullname())}}</strong> declare that the above vehicle
    was my property and it has been
    deregistered. I here by confirm that using the vehicle in the premises of Zanzibar Island is totally illegal
    <br>
    <br>
    Signature
    <br>
    <br>
    Issued by Zanzibar Revenue Authority and signed by <strong>{{strtoupper(auth()->user()->fullname())}}</strong>

</div>

<div class="qr-code">
    <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
</div>

</body>
</html>
