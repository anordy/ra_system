<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Registration - {{ $motor_vehicle->chassis_number }}</title>
    <style>
        body{
            padding: 16px;
            font-family: 'Times New Roman', Times, serif;
            background-image: url("{{ public_path()}}/images/certificate/cert-of-reg.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
            color: blue;
            font-weight: bold;
            font-size: 75px;
        }
        #owner-name{
            position: fixed;
            top: 190px;
            left: -30px;
            background: #f1e5d4;
            width: 1000px;
        }

        #reg-no{
            position: fixed;
            top: 380px;
            left: -30px;
            background: #f1e5d4;
            width: 500px;
        }
        #reg-date{
            position: fixed;
            top: 380px;
            left: 650px;
            background: #f1e5d4;
            width: 500px;
        }
        #plate{
            position: fixed;
            top: 380px;
            left: 1350px;
            background: #f1e5d4;
            width: 500px;
        }
        #yom{
            position: fixed;
            top: 560px;
            left: -30px;
            background: #f1e5d4;
            width: 300px;
        }
        #make{
            position: fixed;
            top: 560px;
            left: 310px;
            background: #f1e5d4;
            width: 300px;
        }
        #model{
            position: fixed;
            top: 650px;
            left: 310px;
            background: #f1e5d4;
            width: 300px;
        }
        #chassis{
            position: fixed;
            top: 740px;
            left: 310px;
            background: #cecdc7;
            width: 900px;
        }
        #style{
            position: fixed;
            top: 830px;
            left: 310px;
            background: #cecdc7;
        }
        #engine{
            position: fixed;
            top: 920px;
            left: 310px;
            background: #cecdc7;
        }
        #color{
            position: fixed;
            top: 830px;
            left: 1350px;
            background: #cecdc7;
            padding-right: 30px;
        }
        #capacity{
            position: fixed;
            top: 920px;
            left: 1350px;
            background: #cecdc7;
            width: 300px;
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
                {{strtoupper($motor_vehicle->tin->fullname ?? $motor_vehicle->tin->taxpayer_name )}}
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
    <div id="chassis">{{strtoupper($motor_vehicle->chassis_number)}}</div>
    <div id="style">{{strtoupper($motor_vehicle->chassis->body_type)}}</div>
    <div id="engine">{{strtoupper($motor_vehicle->chassis->engine_number)}}</div>
    <div id="color">{{strtoupper($motor_vehicle->chassis->color)}}</div>
    <div id="capacity">{{strtoupper($motor_vehicle->chassis->engine_cubic_capacity)}} cc</div>


    </body>
</html>
