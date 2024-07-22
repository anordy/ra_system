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
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 30px;
        }
        .commissioner-name {
            top: 95.5%;
            position: absolute;
            text-transform: capitalize;
            font-weight: bold;
            font-size: 20px;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            margin-left: 55px;
            left: 30px;
        }
        .qr-code {
            top: 86%;
            padding-left: 70px;
            padding-right: 70px;
            text-align: center;
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
        
        <span class="embed approved_on">
                {{$taxClearanceRequest->approved_on ? \Carbon\Carbon::create($taxClearanceRequest->approved_on)->format('d-M-Y') : 'N/A'}}
        </span>
        <span class="embed expired_on">
                {{$taxClearanceRequest->expire_on ? \Carbon\Carbon::create($taxClearanceRequest->expire_on)->format('d-M-Y') : 'N/A'}}
        </span>
        <span class="commissioner-signature">
            <img src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
        </span>
        <span class="commissioner-name">
            {{$commissinerFullName ?? 'N/A'}}
        </span>
    </body>
</html>
