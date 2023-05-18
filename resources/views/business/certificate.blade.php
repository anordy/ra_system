<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Registration - {{ $location->business->name }}</title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/certificate/business_reg.jpg");
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
            font-size: 1.8em;
            top: 32.3%;
        }
        .taxpayer-name {
            font-size: 1.6em;
            top: 41%;
        }
        .taxpayer {
            font-size: 1.6em;
            top: 31.2%;
        }
        .trading-as {
            font-size: 0.8em;
            top: 34.3%;
        }
        .reg-no {
            font-size: 1.5em;
            top: 53%;
        }
        .reg-no-alt {
            font-size: 1.5em;
            top: 52.5%;
        }
        .vrn-no {
            font-size: .8em;
            top: 55.5%;
        }
        .tax-types {
            font-size: 1.6em;
            top: 61.1%;
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
            top: 85%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 30px;
        }
        .commissioner-name {
            top: 93%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
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
            -webkit-transform: rotate(270deg);
            -moz-transform: rotate(270deg);
            -o-transform: rotate(270deg);
            transform: rotate(270deg);
            font-size: 2.5em;
            color: black;
            position: absolute;
            font-family: 'Denk One', sans-serif;
            text-transform: uppercase;
            left: -17%;
            top: 40%;
        }
    </style>
</head>
    <body>
        <span class="embed rc-number">{{ sprintf("%05s", $taxType->id) }}</span>
        @if ($location->is_headquarter == 0)
            <div class="watermark">Branch Copy</div>
        @endif
        <span class="embed taxpayer">{{ $location->business->taxpayer_name ?? '' }}</span>
        <span class="embed trading-as">T/A {{ $location->business->name ?? '' }}</span>
        <span class="embed taxpayer-name">{{ getFormattedTinNo($location) ?? '' }}</span>
        @if($location->vrn)
            <span class="embed reg-no-alt">{{ $location->business->ztn_number ?? '' }}</span>
            <span class="embed vrn-no">VRN NO: {{ $location->vrn ?? '' }}</span>
        @else
            <span class="embed reg-no">{{ $location->business->ztn_number ?? '' }}</span>
        @endif
        <span class="embed tax-types">{{ $tax->name == 'VAT' ? 'VALUE ADDED TAX' : $tax->name }}</span>
        <span class="embed location">
            {{ $location->street->name }}, {{ $location->region->location }}
        </span>
        <span class="embed commencing-date">
            {{ $location->date_of_commencing->format('d/m/Y') }}

        </span>
        <span class="commissioner-signature">
            <img src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
        </span>
        <span class="commissioner-name">
            {{$commissinerFullName}}
        </span>
        <div class="qr-code">
            <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
        </div>
    </body>
</html>
