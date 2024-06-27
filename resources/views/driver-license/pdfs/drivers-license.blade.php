<!DOCTYPE html>
<html>

<head>
    <title>Drivers License - {{ $license->license_number }}</title>
    <style>
        body {
            padding: 16px;
            font-family: 'Times New Roman', Times, serif;
            background-image: url("{{ public_path() }}/images/certificate/drivers-license.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
            color: blue;
            font-weight: bold;
            font-size: 70px;
        }

        .page-two {
            background-image: url("{{ public_path() }}/images/certificate/drivers-license-back.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        #image {
            position: absolute;
            height: 562px;
            width: 520px;
            top: 210px;
            left: -40px;
            overflow: hidden;
        }

        #image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        #owner-name {
            position: absolute;
            top: 290px;
            left: 520px;
        }

        #sex {
            position: absolute;
            top: 480px;
            left: 520px;
            min-width: 130px;
        }

        #dob {
            position: absolute;
            top: 480px;
            left: 1040px;
            min-width: 430px;
        }

        #restrictions {
            position: absolute;
            top: 480px;
            left: 1570px;
            width: 430px;
            overflow: hidden;
            text-align: center;
        }

        #issue {
            position: absolute;
            top: 645px;
            left: 520px;
            min-width: 400px;
        }

        #expiry {
            position: absolute;
            top: 645px;
            left: 1040px;
            min-width: 430px;
        }

        #blood-group {
            position: absolute;
            top: 645px;
            left: 1570px;
            width: 430px;
            overflow: hidden;
            text-align: center;
        }

        #class {
            position: absolute;
            top: 785px;
            left: 720px;
            min-width: 230px;
            color: red;
        }

        #zin {
            position: absolute;
            top: 955px;
            left: -50px;
            width: 480px;
            overflow: hidden;
            text-align: center;
        }

        #pin {
            position: absolute;
            top: 955px;
            left: 750px;
            width: 480px;
            overflow: hidden;
            text-align: center;
        }

        #barcode {
            position: absolute;
            top: 1090px;
            left: 700px;
            width: 480px;
        }

        #duplicate {
            position: absolute;
            top: 1090px;
            left: -0px;
            width: 480px;
        }

        .back-normal {
            color: black;
            font-size: 60px;
            text-align: center;
            font-weight: normal;
            line-height: 1.4em;
        }

        #back-property {
            color: black;
            font-size: 70px;
            text-align: center;
            font-weight: bold;
        }
        #back-enclosure {
            top: 70px;
            position: absolute;
            right: 0;
            left: 0;
        }
        #class-information {
            color: black;
            font-size: 60px;
            line-height: 1.3em;
            position: absolute;
            top: 440px;
            right: 300px;
            left: 300px;
        }
    </style>
</head>
<body>
<div id="image">
    <img src="{{ $base64Image }}" alt="Passport Size">
</div>
<div id="owner-name">{{ strtoupper($license->drivers_license_owner->fullname()) }}</div>
<div id="sex">{{ strtoupper($license->drivers_license_owner->gender ?? 'N/A') }}</div>
<div id="dob">{{ \Carbon\Carbon::parse($license->drivers_license_owner->dob)->format('d/m/Y') }}</div>
<div id="restrictions">
    @foreach($license->licenseRestrictions as $lR)
        {{ $lR->restriction->symbol }} @if(!$loop->last) / @endif
    @endforeach
</div>
<div id="issue">{{ \Carbon\Carbon::parse($license->issued_date)->format('d/m/Y') }}</div>
<div id="expiry">{{ \Carbon\Carbon::parse($license->expiry_date)->format('d/m/Y') }}</div>
<div id="blood-group">{{ $license->drivers_license_owner->blood_group }}</div>
<div id="class">
    @foreach ($license->drivers_license_classes as $class)
        {{ $class->license_class->name }} @if(!$loop->last) / @endif
    @endforeach
</div>

<div id="zin">{{ $license->drivers_license_owner->taxpayer->reference_no ?? 'N/A' }}</div>
<div id="pin">{{ $license->license_number }}</div>
@if($license->status === \App\Models\DlDriversLicense::STATUS_DAMAGED_OR_LOST)
    <div id="duplicate">DUPLICATE</div>
@endif
<div id="barcode">
    <img src="data:image/png;base64,' . {{ DNS1D::getBarcodePNG($license->license_number, 'C39+',4,100, array(1,1,1), false)  }} . '" alt="barcode"   />
</div>
</body>
<body class="page-two" style="page-break-before: always">
<div id="back-enclosure">
    <div class="back-normal">
        This Driver's License is the property of
    </div>
    <div id="back-property">
        The Revolutionary Government of Zanzibar
    </div>
    <div class="back-normal">
        If found, please return to the nearest
    </div>
    <div class="back-normal">
        Police Station or any ZRB Office
    </div>
</div>
<div id="class-information">
    @foreach($license->drivers_license_classes as $class)
        <div>
            {{ $class->license_class->name }} {{ $class->license_class->description }}
        </div>
    @endforeach
</div>
</body>
</html>
