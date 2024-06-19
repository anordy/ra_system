<!DOCTYPE html>
<html>

<head>
    <title>Road License Sticker</title>
    <style>
        body {
            padding: 16px;
            font-family: 'Times New Roman', Times, serif;
            background-image: url("{{ public_path() }}/images/certificate/road_license.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
            font-weight: bold;
            font-size: 30px;
        }

        #no-top {
            position: fixed;
            top: 355px;
            left: 200px;
        }

        #no-inner {
            position: fixed;
            top: 490px;
            left: 720px;
        }

        #plate-number {
            position: fixed;
            top: 490px;
            left: 320px;
        }

        #qr-code {
            position: fixed;
            top: 990px;
            left: 805px;
        }

        #owner-name {
            position: fixed;
            top: 550px;
            left: 320px;
        }

        #category {
            position: fixed;
            top: 620px;
            left: 320px;
            width: 700px;
        }

        #reg-no {
            position: fixed;
            top: 720px;
            left: 320px;
            min-width: 430px;
        }

        #make {
            position: fixed;
            top: 800px;
            left: 320px;
        }

        #pass-mark {
            position: fixed;
            top: 800px;
            left: 805px;
        }

        #model {
            position: fixed;
            top: 890px;
            left: 320px;
        }

        #color {
            position: fixed;
            top: 950px;
            left: 320px;
        }

        #weight {
            position: fixed;
            top: 1010px;
            left: 320px;
        }

        #issued-date {
            position: fixed;
            top: 1090px;
            left: 320px;
        }

        #expiry-date {
            position: fixed;
            top: 1090px;
            left: 605px;
        }

        #paid-amount-words {
            position: fixed;
            top: 1250px;
            left: 440px;
        }

        #paid-amount-number {
            position: fixed;
            bottom: 85px;
            left: 180px;
        }

    </style>
</head>

<body>

<div id="no-top">{{ strtoupper($roadLicense->id) }}</div>
<div id="no-inner">{{ strtoupper($roadLicense->id) }}</div>
<div id="owner-name">{{ strtoupper($roadLicense->taxpayer->fullname() ?? 'N/A') }}</div>
<div id="plate-number">{{ strtoupper($roadLicense->registration->plate_number ?? 'N/A') }}</div>
<div id="color">{{ strtoupper($roadLicense->registration->chassis->color ?? 'N/A') }}</div>
<div id="reg-no">{{ strtoupper($roadLicense->registration->registration_number ?? 'N/A') }}</div>
<div id="weight">{{ strtoupper($roadLicense->registration->chassis->gross_weight ?? 'N/A') }}</div>
<div id="make">{{ strtoupper($roadLicense->registration->chassis->make ?? 'N/A') }}</div>
<div id="pass-mark">Pass: {{ strtoupper($roadLicense->pass_mark ?? 'N/A') }}</div>
<div id="model">{{ strtoupper($roadLicense->registration->chassis->model_type ?? 'N/A') }}</div>
<div id="issued-date">{{ \Carbon\Carbon::parse($roadLicense->issued_date)->format('d/m/Y') }}</div>
<div id="expiry-date">{{ \Carbon\Carbon::parse($roadLicense->expire_date)->format('d/m/Y') }}</div>
<div id="category">{{ strtoupper($roadLicense->registration->class->name ?? 'N/A') }} ({{ strtoupper($roadLicense->registration->regtype->name ?? 'N/A') }})</div>
<div id="paid-amount-words">

</div>
<div id="paid-amount-number">
</div>
<div id="qr-code">
     <img src="{{ $dataUri }}" alt="QR Code" width="150" height="150">
</div>

</body>

</html>
