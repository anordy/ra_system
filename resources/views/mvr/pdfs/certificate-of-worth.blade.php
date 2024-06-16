<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Road Worthiness - {{ $motor_vehicle->chassis_number }}</title>
    <style>
        body{
            padding: 16px;
        }
        .logo{
            text-align: center;
        }
        .logo img{
            height: 150px;
        }
        .text-center{
            text-align: center;
        }
        .title1 {
            font-weight: bold;
            padding: 14px;
        }
        .statement{
            font-size: 26px;
            text-align: justify;
            color: #333333;
            line-height: 1.5;
        }
        .mv-details-wrapper{
            width: 100%;
            margin-top: 50px;
        }
        .mv-details-wrapper > div{
            display: inline-grid;
            width: 49.5%;
        }
        table  td div{
            padding: 20px 10px;
            font-weight: bold;
            font-size: 22px;
            width: 100%;
        }
        td {
            vertical-align: top;
            width: 580px;
        }
        td span{
            display: inline-block;
        }
        .commissioner-name {
            text-transform: capitalize;
        }

        .width-100 {
            width: 100px;
        }
    </style>
</head>
    <body>
        <div  class="logo">
            <img src="{{public_path()}}/logo/zbs.png">
        </div>
        <div class="text-center title1">ZANZIBAR BUREAU OF STANDARDS</div>
        <div class="text-center title1">P.O.BOX 1136, AMANI INDUSTRIAL PARK ZANZIBAR</div>
        <div class="text-center title1">TEL: +255242232225, FAX: +255242232225</div>
        <div class="text-center title1">EMAIL: info@zbs.go.tz</div>
        <br/>
        <div class="text-center title1">
            CERTIFICATE OF ROAD WORTHINESS
        </div>

        <br/>
        <div class="statement">
            This is to certify that the undermentioned vehicle has been inspected at our premises and found to meet the requirements of ZNS 98:2015 Road vehicle code of practice for inspection and testing of use motor vehicles for road worthiness
        </div>
        <div class="mv-details-wrapper">
            <table>
                <tr>
                    <td>
                        <div>Make: {{strtoupper($motor_vehicle->chassis->make)}}</div>
                        <div>Year of Manufacture: {{$motor_vehicle->chassis->year}}</div>
                        <div>Chassis: {{$motor_vehicle->chassis_number}}</div>
                        <div>Inspected Mileage: {{$motor_vehicle->mileage}} Km</div>
                        <div>Inspected Date: {{$motor_vehicle->inspection_date}}</div>
                        <div>Receipt Number: {{$motor_vehicle->registration_number}}</div>
                        <br>
                        <div>
                            <img src="{{public_path()}}/logo/zbs.png" height="80px">
                            <br>
                            <br>
                            ZNS 98:2015
                        </div>
                    </td>
                    <td>
                        <div><span class="width-100">Modal: </span> <span>{{strtoupper($motor_vehicle->chassis->model_type)}}</span></div>
                        <div><span class="width-100">Capacity: </span> <span>{{$motor_vehicle->chassis->engine_cubic_capacity ?? 'N/A'}} cc</span></div>
                        <div><span class="width-100">Engine: </span> <span>{{$motor_vehicle->chassis->engine_number}}</span></div>
                        <div><span class="width-100">Type: </span> <span>{{$motor_vehicle->chassis->body_type}}</span></div>
                        <div><span class="width-100">Color: </span> <span>{{$motor_vehicle->chassis->color}}</span></div>
                        <div><span class="width-100">Cert No.: </span> <span>{{$motor_vehicle->certificate_number}}</span></div>
                    </td>
                </tr>
            </table>

        </div>
        <br>
        <br>
       <div class="text-center">
            <span class="commissioner-signature">
                <img src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
            </span>
           <span class="commissioner-name">
                {{$commissinerFullName}}
            </span>
           <br>
           <em>This certificate is valid for the period of three(3) months only</em>
       </div>

    </body>
</html>
