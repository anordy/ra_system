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
            left: -30px;
            width: 1000px;
        }

        #reg-no {
            position: absolute;
            top: 380px;
            left: -30px;
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
            left: -30px;
            width: 300px;
        }

        #make {
            position: absolute;
            top: 560px;
            left: 310px;
            width: 300px;
        }

        #model {
            position: absolute;
            top: 650px;
            left: 310px;
            width: 300px;
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
            padding-right: 30px;
        }

        #capacity {
            position: absolute;
            top: 920px;
            left: 1350px;
            width: 300px;
        }

        #page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

<div id="owner-name">
    @if($motor_vehicle->agent)
        @if($motor_vehicle->is_agent_registration)
            @if($motor_vehicle->use_company_name)
                {{strtoupper($motor_vehicle->agent->company_name ?? 'N/A')}}
            @else
                {{strtoupper($motor_vehicle->taxpayer->fullname ?? 'N/A')}}
            @endif
        @else
            @if($motor_vehicle->tin)
                {{strtoupper($motor_vehicle->tin->fullname ?? $motor_vehicle->tin->taxpayer_name )}}
            @else
                {{ 'N/A'  }}
            @endif
        @endif
    @else
        {{strtoupper($motor_vehicle->taxpayer->fullname ?? 'N/A')}}
    @endif
</div>
<div id="reg-no">{{strtoupper($motor_vehicle->registration_number)}}</div>
<div id="reg-date">{{strtoupper(\Carbon\Carbon::parse($motor_vehicle->registered_at)->format('d/m/Y'))}}</div>
<div id="plate">{{strtoupper($motor_vehicle->plate_number)}}</div>
<div id="yom">{{strtoupper($motor_vehicle->chassis->year ?? '')}}</div>
<div id="make">{{strtoupper($motor_vehicle->chassis->make)}}</div>
<div id="model">{{strtoupper($motor_vehicle->chassis->model_type)}}</div>
<div id="chassis">{{strtoupper($motor_vehicle->chassis->chassis_number)}}</div>
<div id="style">{{strtoupper($motor_vehicle->chassis->body_type)}}</div>
<div id="engine">{{strtoupper($motor_vehicle->chassis->engine_number)}}</div>
<div id="color">{{strtoupper($motor_vehicle->chassis->color)}}</div>
<div id="capacity">{{strtoupper($motor_vehicle->chassis->engine_cubic_capacity)}} cc</div>

</body>
<body class="page-two" style="page-break-before: always">
</body>
</html>
