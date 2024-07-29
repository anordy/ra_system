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

        .page-two {
            background-image: url("{{ public_path() }}/images/certificate/back_page.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            page-break-before: always
            /*margin: -70px;*/
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
            font-size: 1.3em;
            left: 10%;
            top: 42.75%;
        }

        .taxpayer {
            font-size: 1.4em;
            top: 38%;
        }

        .taxpayer-alt {
            font-size: 1.6em;
            top: 39%;
        }

        .trading-as {
            font-size: 0.8em;
            top: 41%;
        }

        .reg-no {
            font-size: 2em;
            top: 51%;
        }

        .reg-no-alt {
            font-size: 2em;
            top: 51%;
        }

        .vrn-no {
            font-size: .8em;
            top: 55%;
        }

        .tax-types {
            font-size: 1em;
            top: 60.3%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 80%;
            padding-left: 70px;
            padding-right: 70px;
            left: 15%;
        }

        .location {
            top: 66.2%;
            font-size: 1em;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 80%;
            padding-left: 70px;
            padding-right: 70px;
            left: 29%;
        }

        .zra-location {
            font-size: 1em;
            top: 69.2%;
            left: -24%;
        }

        .tax-region {
            font-size: 1em;
            top: 72.1%;
            left: -22%;
        }

        .commencing-date {
            font-size: 1em;
            top: 63.25%;
            left: -12.0%;
        }

        .on-hand-date {
            font-size: 0.8em;
            top: 85.7%;
            left: 58%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 80%;
            padding-left: 70px;
            padding-right: 70px;
        }


        .commissioner-signature {
            top: 73%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 55.5%;
        }

        .commissioner-name {
            top: 80.5%;
            position: absolute;
            text-transform: capitalize;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
            left: 53.5%;
        }

        .commissioner-title {
            top: 82%;
            position: absolute;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
            left: 50.5%;
        }

        .qr-code {
            overflow: hidden;
            position: absolute;
            top: 77%;
            right: 76%;
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

        .online-copy {
            -webkit-transform: rotate(331deg);
            -moz-transform: rotate(331deg);
            -o-transform: rotate(331deg);
            transform: rotate(331deg);
            font-size: 6em;
            color: rgba(255, 5, 5, 0.37);
            position: absolute;
            font-family: 'Denk One', sans-serif;
            text-transform: uppercase;
            padding-left: 10%;
            top: 40%;
        }

        .multiline-font-size {
            font-size: 1.1em !important;
        }

        .qr-code-height {
            height: 189px
        }
    </style>
</head>
<body>
<span class="embed rc-number">{{ sprintf("%05s", $taxType->id) }}</span>
@if ($location->is_headquarter == 0)
    <div class="watermark">Branch Copy</div>
@endif
@if(isset($location->business->name) && isset($location->business->taxpayer_name) && strtolower(trim($location->business->name)) != strtolower(trim($location->business->taxpayer_name)))
    <span class="embed taxpayer @if(strlen($location->business->name) > 45) multiline-font-size @endif">{{ ($location->business->taxpayer_name ? $location->business->taxpayer_name : $location->business->name) ?? '' }}</span>
    <span class="embed trading-as @if(strlen($location->business->name) > 45) multiline-font-size @endif">T/A {{ $location->business->name }}</span>
@else
    <span class="embed taxpayer-alt @if(strlen($location->business->name) > 45) multiline-font-size @endif">{{ ($location->business->taxpayer_name ? $location->business->taxpayer_name : $location->business->name) ?? '' }}</span>
@endif
<span class="embed taxpayer-name">{{ getFormattedTinNo($location) ?? '' }}</span>
@if($location->vrn)
    <span class="embed reg-no-alt">{{ $location->business->ztn_number ?? '' }}</span>
    <span class="embed vrn-no">VRN NO: {{ $location->vrn ?? '' }}</span>
@else
    <span class="embed reg-no">{{ $location->business->ztn_number ?? '' }}</span>
@endif
<span class="tax-types">{{ $tax->name == 'VAT' ? 'VALUE ADDED TAX' : $tax->name }}</span>
<span class="location">
        {{ $location->street->name }} - {{ $location->ward->name }}
    </span>
<span class="embed zra-location">
        {{ $location->region->location }}
    </span>
<span class="embed tax-region">
        {{ $location->taxRegion->name }}
    </span>
<span class="embed commencing-date">
        {{ $location->date_of_commencing->format('d F Y') }}
    </span>
<span class="on-hand-date">
        {{ now()->format('d F, Y') }}
    </span>
<div class="signature">
<span class="commissioner-signature">
        <img src="{{ $signaturePath }}" width="300">
    </span>
<span class="commissioner-name">
        {{$commissinerFullName}}
    </span>
<span class="commissioner-title">
        {{ $title }}
    </span>
</div>
<div class="qr-code">
    <img class="img-fluid qr-code-height" src="{{ $dataUri }}">
    </div>
</body>
<body class="page-two">
</body>
</html>
