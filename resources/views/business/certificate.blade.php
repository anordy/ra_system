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
            right: 23%;
        }
        .business-name {
            font-size: 1.8em;
            top: 32.3%;
        }
        .taxpayer-name {
            font-size: 1.4em;
            left: 3%;
            top: 46%;
        }
        .taxpayer {
            font-size: 1.6em;
            top: 40.2%;
        }
        .trading-as {
            font-size: 0.8em;
            top: 44%;
        }
        .reg-no {
            font-size: 1.7em;
            top: 59%;
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
            font-size: 1.1em;
            top: 65%;
            left: -6%;
        }
        .location {
            font-size: 1.1em;
            top: 70.8%;
            left: 11.4%;
        }
        .zra-location {
            font-size: 1.1em;
            top: 73.5%;
            left: -12.4%;
        }
        .commencing-date {
            font-size: 1.1em;
            top: 67.7%;
            left: -7.5%;
        }
        .on-hand-date {
            font-size: 0.8em;
            top: 92.2%;
            left: 9%;
        }
        .commissioner-signature {
            top: 79%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 65%;
        }
        .commissioner-name {
            top: 86.5%;
            position: absolute;
            text-transform: capitalize;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
            left: 60%;
        }
        .commissioner-title {
            top: 88%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
            left: 59.5%;
        }
        .qr-code {
            overflow: hidden;
            position:absolute;
            top: 79%;
            right: 78%;
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
        <span class="embed taxpayer">{{ ($location->business->taxpayer_name ? $location->business->taxpayer_name : $location->business->name) ?? '' }}</span>
        @if($location->business->name)
            <span class="embed trading-as">T/A {{ $location->business->name ?? '' }}</span>
        @endif
        <span class="embed taxpayer-name">{{ getFormattedTinNo($location) ?? '' }}</span>
        @if($location->vrn)
            <span class="embed reg-no-alt">{{ $location->business->ztn_number ?? '' }}</span>
            <span class="embed vrn-no">VRN NO: {{ $location->vrn ?? '' }}</span>
        @else
            <span class="embed reg-no">{{ $location->business->ztn_number ?? '' }}</span>
        @endif
        <span class="embed tax-types">{{ $tax->name == 'VAT' ? 'VALUE ADDED TAX' : $tax->name }}</span>
        <span class="embed location">
            {{ $location->street->name }}-{{ $location->ward->name }} - {{ $location->region->location }}
        </span>
        <span class="embed zra-location">
            {{ $location->region->location }}
        </span>
        <span class="embed commencing-date">
            {{ $location->date_of_commencing->format('d F Y') }}
        </span>
        <span class="embed on-hand-date">
            {{ $location->date_of_commencing->format('d F, Y') }}
        </span>
        <span class="commissioner-signature">
            <img src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
        </span>
        <span class="commissioner-name">
            {{$commissinerFullName}}
        </span>
        <span class="commissioner-title">
            COMMISSIONER GENERAL
        </span>
        <div class="qr-code">
            <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
        </div>
    </body>
</html>
