<!DOCTYPE html>
<html>
<head>
    <title>Tax Clearance Certificate - {{ $location->business->name ?? 'N/A' }}</title>
    <style>
        body {
            background-image: url("{{ public_path()}}/images/certificate/tax_clearance.jpg");
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
        .cert-no {
            font-size: 1.15em;
            top: 25.4%;
            left: 18%;
        }
        .business-name {
            font-size: 1.15em;
            top: 46%;
        }
        .company-name {
            font-size: 1.5em;
            top: 40%;
        }
        .reg-no {
            font-size: 1.5em;
            top: 51%;
        }
        .vrn-no {
            font-size: 1.5em;
            top: 56%;
        }

        .approved_on {
            font-size: 1.5em;
            top: 76%;
        }

        .expired_on {
            font-size: 1.5em;
            top: 82%;
        }

        .tax-types {
            font-size: 1.1em;
            top: 61%;
        }
        .commissioner-signature {
            top: 88%;
            position: absolute;
            padding-left: 70px;
            padding-right: 70px;
            left: 30px;
        }
        .commissioner-name {
            top: 95.5%;
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
        .commissioner-title {
            top: 84.7%;
            position: absolute;
            font-weight: bold;
            font-size: 26px;
            width: 100%;
            padding-left: 60px;
            padding-right: 70px;
            margin-left: 10px;
            left: 1%;
        }
        .qr-code {
            overflow: hidden;
            position:absolute;
            top: 77%;
            left: 76%;
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
<p class="watermark">Online Copy</p>
<span class="embed business-name">{{ $location->name ?? '' }}</span>
<span class="embed company-name">{{ $location->business->name ?? '' }}</span>
<span class="embed reg-no">{{ $location->zin ?? '' }}</span>
<span class="embed cert-no">{{ $taxClearanceRequest->certificate_number ?? '' }}</span>
<span class="embed vrn-no">{{ $location->business->vrn ?? 'N/A' }}</span>

<span class="embed approved_on">
                {{$taxClearanceRequest->approved_on ? \Carbon\Carbon::create($taxClearanceRequest->approved_on)->format('d-M-Y') : 'N/A'}}
        </span>
<span class="embed expired_on">
                {{$taxClearanceRequest->expire_on ? \Carbon\Carbon::create($taxClearanceRequest->expire_on)->format('d-M-Y') : 'N/A'}}
        </span>
<span class="commissioner-signature">
        <img src="{{ $signaturePath }}" width="300" alt="Signature">
    </span>
<span class="commissioner-name">
        {{$commissinerFullName ?? ''}}
    </span>
<span class="commissioner-title">
        {{ $title ?? '' }}
    </span>
<div class="qr-code">
    <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
</div>
</body>
</html>
