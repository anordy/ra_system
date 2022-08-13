<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/certificate/tax_consultant.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
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
            top: 50%;
        }

        .taxpayerName {
            position: absolute;
            font-size: 1.2em;
            text-align: center;
            top: 33%;
            left: 38%;
            text-transform: uppercase;
            font-weight: bold
        }

        .period {
            position: absolute;
            font-size: 1em;
            top: 62.8%;
            left: 45.8%;
            text-transform: uppercase;
            font-weight: bold
        }

        .startDay {
            position: absolute;
            font-size: 1em;
            top: 66.1%;
            left: 24.5%;
            text-transform: uppercase;
            font-weight: bold
        }

        .sup{
            text-transform: lowercase;
        }

        .startYear {
            position: absolute;
            font-size: 1em;
            top: 66.1%;
            left: 39.5%;
            text-transform: uppercase;
            font-weight: bold
        }

        .endday {
            position: absolute;
            font-size: 1em;
            top: 66.1%;
            left: 60.3%;
            text-transform: uppercase;
            font-weight: bold
        }
        .endyear {
            position: absolute;
            font-size: 1em;
            top: 66.1%;
            left: 73.8%;
            text-transform: uppercase;
            font-weight: bold
        }

        .location {
            position: absolute;
            font-size: 1em;
            top: 72.2%;
            left: 38.5%;
            text-transform: uppercase;
            font-weight: bold
        }

        .registrationNumber {
            position: absolute;
            font-size: 1.4em;
            top: 40.5%;
            left: 44.3%;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 2px;
        }
    </style>
</head>

<body>d

<span class="taxpayerName">{{implode(" ", array($taxagent->taxpayer->first_name,$taxagent->taxpayer->middle_name,$taxagent->taxpayer->last_name))}}</span>
<span class="registrationNumber">{{$taxagent->reference_no}}</span>
<span class="period">@if($taxagent->is_first_application == 1) 1 year @endif</span>
<span class="startDay">{{date('d', strtotime($taxagent->app_first_date))}}<sup class="sup">{{$superStart}}</sup></span>
<span class="startYear">{{date('M Y', strtotime($taxagent->app_first_date))}}</span>
<span class="endday">{{date('d', strtotime($taxagent->app_expire_date))}}<sup class="sup">{{$superEnd}}</sup></span>
<span class="endyear">{{date('M Y', strtotime($taxagent->app_expire_date))}}</span>
<span class="location">{{$taxagent->district->name.', '.$taxagent->region->name}}</span>

</body>

</html>
