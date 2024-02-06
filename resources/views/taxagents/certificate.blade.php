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

        .taxpayerName {
            font-size: 1.2em;
            text-align: center;
            top: 33%;
            position: absolute;
            margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;
            text-transform: uppercase;
            font-weight: bold
        }

        .period {
            position: absolute;
            font-size: 1em;
            top: 62.8%;
            left: 43.9%;
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
            left: 40.3%;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .commissioner-signature {
            top: 86%;
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
            left: 30px;
        }
        .commissioner-name {
            top: 93%;
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
     
    </style>
</head>

<body>
    <span class="taxpayerName">{{ $taxagent->taxpayer->fullName }}</span>
    <span class="registrationNumber">{{$taxagent->reference_no}}</span>
    <span class="period">{{$diff.' '.$word}}</span>
    <span class="startDay">{{date('d', strtotime($start_date))}}<sup class="sup">{{$superStart}}</sup></span>
    <span class="startYear">{{date('M Y', strtotime($start_date))}}</span>
    <span class="endday">{{date('d', strtotime($end_date))}}<sup class="sup">{{$superEnd}}</sup></span>
    <span class="endyear">{{date('M Y', strtotime($end_date))}}</span>
    <span class="location">{{$taxagent->district->name.', '.$taxagent->region->name}}</span>
    <span class="commissioner-signature">
        <img src="{{ $signaturePath == '/sign/commissioner.png' ? public_path() . '/sign/commissioner.png': storage_path().'/app/'. $signaturePath}}">
    </span>
    <span class="commissioner-name">
        {{$commissinerFullName}}
    </span>
    <div style="overflow: hidden; position:absolute; top: 81%; left: 44%; background: white; border-radius: 5px; height: 180px; width: 180px; padding: 5px">
        <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
    </div>
</body>

</html>
