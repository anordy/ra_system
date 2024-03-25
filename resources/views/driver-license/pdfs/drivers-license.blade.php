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
            font-size: 73px;
        }

        #owner-name {
            position: fixed;
            top: 290px;
            left: 520px;
            background: #d3dfef;
            width: 800px;
        }

        #sex {
            position: fixed;
            top: 480px;
            left: 520px;
            background: #d3dfef;
            min-width: 130px;
        }

        #dob {
            position: fixed;
            top: 480px;
            left: 1040px;
            background: #b1b3b2;
            min-width: 430px;
        }

        #restrictions {
            position: fixed;
            top: 480px;
            left: 1570px;
            background: #b1b3b2;
            width: 430px;
            overflow: hidden;
            text-align: center;
        }

        #issue {
            position: fixed;
            top: 645px;
            left: 520px;
            background: #ddd9da;
            min-width: 400px;
        }

        #expiry {
            position: fixed;
            top: 645px;
            left: 1040px;
            background: #ddd9da;
            min-width: 430px;
        }

        #blood-group {
            position: fixed;
            top: 645px;
            left: 1570px;
            background: #ddd9da;
            width: 430px;
            overflow: hidden;
            text-align: center;
        }

        #class {
            position: fixed;
            top: 785px;
            left: 720px;
            background: #b7bec5;
            min-width: 230px;
            color: red;
        }

        #zin {
            position: fixed;
            top: 955px;
            left: -50px;
            background: #bac7d5;
            width: 480px;
            overflow: hidden;
            text-align: center;
        }

        #image {
            position: fixed;
            height: 562px;
            width: 520px;
            top: 210px;
            left: -40px;
            overflow: hidden;
        }

        #image img {
            min-height: 100%;
            min-width: 100%;
        }
    </style>
</head>

<body>

    <div id="image">
        <img src="{{ public_path() . '/storage/' . $license->drivers_license_owner->photo_path }}" alt="">
    </div>
    <div id="owner-name">{{ strtoupper($license->drivers_license_owner->fullname()) }}</div>
    <div id="sex">{{ strtoupper($license->drivers_license_owner->sex ?? 'Male') }}</div>
    <div id="dob">{{ \Carbon\Carbon::parse($license->drivers_license_owner->dob)->format('d/m/Y') }}</div>
    <div id="restrictions">{{ $license->license_restrictions }}</div>
    <div id="issue">{{ \Carbon\Carbon::parse($license->issued_date)->format('d/m/Y') }}</div>
    <div id="expiry">{{ \Carbon\Carbon::parse($license->expiry_date)->format('d/m/Y') }}</div>
    <div id="blood-group">{{ $license->drivers_license_owner->blood_group }}</div>
    <div id="class">
        @foreach ($license->drivers_license_classes as $class)
            {{ $class->license_class->name }}
        @endforeach
    </div>

    <div id="zin">{{ $license->drivers_license_owner->reference_no }}</div>

</body>

</html>
