<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Registration - {{ $location->business->name }}</title>
    <style>
        body {
            background-image: url("{{ public_path()}}/images/certificate/business_reg.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
        }
        .embed {
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
        }
        .rc-number {
            font-size: 1.15em;
            top: 3.3%;
            text-align: right;
            right: 20px;
        }
        .business-name {
            font-size: 1.15em;
            top: 33.5%;
        }
        .taxpayer-name {
            font-size: 1.5em;
            top: 41%;
        }
        .reg-no {
            font-size: 1.5em;
            top: 53%;
        }
        .tax-types {
            font-size: 1.1em;
            top: 61%;
        }
        .location {
            font-size: 1.2em;
            top: 72%;
        }
        .commencing-date {
            font-size: 1.2em;
            top: 80%;
            padding-left: 90px;
        }
        .commissioner-signature {
            top: 86%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 30px;
        }
        .qr-code {
            overflow: hidden;
            position:absolute;
            top: 83%;
            left: 44%;
            background: white;
            border-radius: 5px;
            height: 180px;
            width: 180px;
            padding: 5px;
        }
        .watermark {
            -webkit-transform: rotate(331deg);
            -moz-transform: rotate(331deg);
            -o-transform: rotate(331deg);
            transform: rotate(331deg);
            font-size: 6em;
            color: rgba(255, 5, 5, 0.17);
            position: absolute;
            font-family: 'Denk One', sans-serif;
            text-transform: uppercase;
            padding-left: 10%;
            top: 40%;
        }
    </style>
</head>
    <body>
        <span class="embed rc-number">{{ sprintf("%05s", $taxType->id) }}</span>
        <span class="embed business-name">{{ $location->business->name ?? '' }}</span>
        <span class="embed taxpayer-name">{{ $location->business->tin ?? '' }}</span>
        <span class="embed reg-no">{{ $certificateNumber ?? '' }}</span>
        <span class="embed tax-types">{{ $tax->name }}</span>
        <span class="embed location">
            {{ $location->district->name }}, {{ $location->region->name }}
        </span>
        <span class="embed commencing-date">
            {{ $location->date_of_commencing->toFormattedDateString() }}
        </span>
        <span class="commissioner-signature">
            <img src="{{ public_path()}}/sign/commissioner.png">
        </span>
        <div class="qr-code">
            <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
        </div>
    </body>
</html>
