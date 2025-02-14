<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Registration - {{ $motor_vehicle->chassis_number }}</title>
    <style>
        body {
            padding: 16px;
            font-family: 'Times New Roman', Times, serif;
            background-image: url("{{ public_path()}}/images/certificate/cert-of-reg.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
            color: blue;
            font-weight: bold;
            font-size: 70px;
        }

        .page-two {
            background-image: url("{{ public_path() }}/images/certificate/cert-of-reg-back.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        #owner-name {
            position: absolute;
            top: 190px;
            left: 1px;
            width: 100%;
        }

        #reg-no {
            position: absolute;
            top: 380px;
            left: 1px;
            width: 500px;
        }

        #reg-date {
            position: absolute;
            top: 380px;
            left: 650px;
            width: 500px;
        }

        #plate {
            position: absolute;
            top: 380px;
            left: 1350px;
            width: 500px;
        }

        #yom {
            position: absolute;
            top: 560px;
            left: 1px;
            width: 300px;
        }

        #make {
            position: absolute;
            top: 560px;
            left: 310px;
        }

        #model {
            position: absolute;
            top: 650px;
            left: 310px;
        }

        #chassis {
            position: absolute;
            top: 740px;
            left: 310px;
            width: 900px;
        }

        #style {
            position: absolute;
            top: 830px;
            left: 310px;
        }

        #engine {
            position: absolute;
            top: 920px;
            left: 310px;
        }

        #color {
            position: absolute;
            top: 830px;
            left: 1350px;
            font-size: 60px;
        }

        #capacity {
            position: absolute;
            top: 920px;
            left: 1350px;
            width: 300px;
        }

        #barcode {
            position: fixed;
            top: 1070px;
            left: 700px;
            width: 480px;
        }

        #page-break {
            page-break-before: always;
        }

        #qr-code {
            position: fixed;
            top: 960px;
            right: -35px;
            border-radius: 5px;
            height: 180px;
            width: 180px;
        }
    </style>
</head>
<body>
<div id="owner-name">
    {{strtoupper($motor_vehicle->taxpayer->full_name ?? 'N/A')}}
</div>
<div id="reg-no">{{strtoupper($motor_vehicle->registration_number)}}</div>
<div id="reg-date">{{strtoupper(\Carbon\Carbon::parse($motor_vehicle->registered_at)->format('d/m/Y'))}}</div>
<div id="plate">{{strtoupper($motor_vehicle->plate_number)}}</div>
<div id="yom">{{strtoupper($motor_vehicle->chassis->vehicle_manufacture_year ?? '')}}</div>
<div id="make">{{strtoupper($motor_vehicle->chassis->makeTypeTra->name ?? '')}}</div>
<div id="model">{{strtoupper($motor_vehicle->chassis->modelTypeTra->name ?? '')}}</div>
<div id="chassis">{{strtoupper($motor_vehicle->chassis->chassis_number ?? '')}}</div>
<div id="style">{{strtoupper($motor_vehicle->chassis->bodyTypeTra->name ?? '')}}</div>
<div id="engine">{{strtoupper($motor_vehicle->chassis->engine_number ?? '')}}</div>
<div id="color">{{strtoupper($motor_vehicle->chassis->colorTypeTra->name ?? '')}}</div>
<div id="capacity">{{strtoupper($motor_vehicle->chassis->engine_capacity ?? '')}} cc</div>
<div id="barcode">
    <img src="data:image/png;base64,' . {{ DNS1D::getBarcodePNG($motor_vehicle->registration_number, 'C39+',4,100, array(1,1,1), false)  }} . '"
         alt="barcode"/>
</div>
<div id="qr-code">
    <img class="img-fluid" src="{{ $dataUri }}" alt="qr-code"/>
</div>
</body>
<body class="page-two" style="page-break-before: always">
</body>
</html>
