<!DOCTYPE html>
<html>

<head>
    <title>Certificate of De-registration - {{ $motor_vehicle->chassis_number }}</title>
    <style>
        body{
            padding: 16px;
            font-family: sans-serif;
        }
        .logo{
            text-align: center;
            display: inline;
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

        .mv-details-wrapper{
            width: 100%;
            margin-top: 20px;
        }
        .mv-details-wrapper > div{
            display: inline-grid;
            width: 49.5%;
        }
        table.owner-details td div{
            padding: 20px 10px;
            font-weight: bold;
            font-size: 22px;
            width: 100%;
        }
        table.owner-details td {
            vertical-align: top;
            width: 290px;
            padding: 8px;
        }
        table.owner-details td:nth-child(2),table.owner-details td:nth-child(4) {
            font-weight: bold;
            width: 240px;
        }
        td span{
            display: inline-block;
        }
        .clearfix {
            overflow: auto;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        .mv-details-wrapper tr td:first-child{
            font-weight: bold;
            text-align: right;
            font-size: 22px;
        }
        .mv-details-wrapper tr td:first-child{
            padding: 8px;
        }
    </style>
</head>
    <body>

    <div class="header">
        <div class="title1" style="float: left">
            <div  class="logo" style="float: left">
                <img src="{{public_path()}}/images/logo.jpg">
            </div>
            <div style="display: inline; margin-left: 170px;">
                Zanzibar Revenue Authority
            </div>
        </div>
        <div style="float: right">
            <div class="title1">
                Mamlaka ya Mapato Zanzibar
            </div>
            <div style="text-align: right; padding-right: 16px">
                Date: {{\Carbon\Carbon::parse($request->certificate_date)->format('d M,Y')}}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
        <div class="text-center title1">VEHICLE DEREGISTRATION CERTIFICATE</div>
        <div class="text-center title1">(made under Section 47 and Regulation 25)</div>

        <br/>
    <table class="owner-details">
        <tr>
            <td>Vehicle Owner</td>
            <td colspan="3">{{strtoupper($motor_vehicle->last_owner->taxpayer->fullname())}}</td>
        </tr>
        <tr>
            <td>Registration Date</td>
            <td >{{$motor_vehicle->registration_date}}</td>
            <td>Registration Number:</td>
            <td>{{$motor_vehicle->registration_number}}</td>
        </tr>
        <tr>
            <td>Current Plate Number</td>
            <td colspan="3">{{$motor_vehicle->current_registration->plate_number}}</td>

        </tr>
        <tr>
            <td>Postal Address (if known)</td>
            <td colspan="3">1</td>
        </tr>
    </table>

    <br>
    <br>
    <span style="border-bottom: 1px solid black; padding:0 8px;">VEHICLE DETAILS</span>
        <div class="mv-details-wrapper">
            <table>
                <tr><td>MANUFACTURER: </td><td>{{strtoupper($motor_vehicle->model->make->name)}}</td></tr>
                <tr><td>MODEL: </td><td>{{strtoupper($motor_vehicle->model->name)}}</td></tr>
                <tr><td>STYLE: </td><td>{{strtoupper($motor_vehicle->body_type->name)}}</td></tr>
                <tr><td>CLASSES: </td><td>{{strtoupper($motor_vehicle->class->name)}}</td></tr>
                <tr><td>COLOR: </td><td>{{strtoupper($motor_vehicle->color->name)}}</td></tr>
                <tr><td>MANUFACTURE DATE: </td><td>{{strtoupper($motor_vehicle->year_of_manufacture)}}</td></tr>
                <tr><td>CHASSIS NUMBER: </td><td>{{strtoupper($motor_vehicle->chassis_number)}}</td></tr>
                <tr><td>ENGINE NUMBER: </td><td>{{strtoupper($motor_vehicle->engine_number)}}</td></tr>
                <tr><td>GROSS WEIGHT: </td><td>{{strtoupper($motor_vehicle->gross_weight)}}</td></tr>
                <tr><td>SEATING CAPACITY: </td><td>{{strtoupper($motor_vehicle->seating_capacity)}}</td></tr>
                <tr><td>COUNTRY OF ORIGIN: </td><td>{{strtoupper($motor_vehicle->imported_from_country->name)}}</td></tr>
                <tr><td>PREVIOUS REG NO: </td><td></td></tr>
            </table>
        </div>
    <br>
    {{$request->description}}
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>

    <hr>
       <div style="text-align: justify">
           I  <strong>{{strtoupper($motor_vehicle->last_owner->taxpayer->fullname())}}</strong>  declare that the above vehicle was my property and it has been
           deregistered. I here by confirm that using the vehicle in the premises of Zanzibar Island is totally illegal
           <br>
           <br>
           Signature
           <br>
           <br>
           Issued by ZANZIBAR Revenue Authority and signed by <strong>{{strtoupper(auth()->user()->fullname())}}</strong>

       </div>

    </body>
</html>
