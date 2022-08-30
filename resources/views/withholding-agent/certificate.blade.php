<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/certificate/withholding_agent.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
        }
        .institutionName {
            position: absolute;
            font-size: 1.5em;
            top: 38%;
            left: 30%;
            text-transform: uppercase;
            font-weight: bold
        }

        .witholdingAgentNumber {
            position: absolute;
            font-size: 1.5em;
            top: 70%;
            left: 43%;
            text-transform: uppercase;
            font-weight: bold
        }
        .startDate {
            position: absolute;
            font-size: 1.2em;
            top: 75%;
            left: 43%;
            text-transform: uppercase;
            font-weight: bold
        }

        .dateGiven {
            position: absolute;
            font-size: 1.2em;
            top: 80%;
            left: 43%;
            text-transform: uppercase;
            font-weight: bold
        }
        .location {
            position: absolute;
            font-size: 1.1em;
            top: 46.7%;
            left: 35%;
            text-transform: uppercase;
            font-weight: bold
        }
        .qr-code {
            top: 86%;
            padding-left: 70px;
            padding-right: 70px;
            text-align: center;
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
    </style>
</head>

<body>

    <span class="institutionName">{{ $wa_responsible_person->withholdingAgent->institution_name  ?? '' }}</span>
    <span class="location">{{ $wa_responsible_person->withholdingAgent->institution_place ?? '' }}</span>
    <span class="witholdingAgentNumber">{{ $wa_responsible_person->withholdingAgent->wa_number ?? '' }}</span>
    <span class="startDate">{{ $wa_responsible_person->created_at->toFormattedDateString() ?? '' }}</span>
    <span class="dateGiven">{{ date('M d, Y') }}</span>
    <span class="commissioner-signature">
        <img src="{{ public_path()}}/sign/commissioner.png">
    </span>
    <div style="overflow: hidden; position:absolute; top: 83%; left: 44%; background: white; border-radius: 5px; height: 180px; width: 180px; padding: 5px">
        <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
    </div>
</body>

</html>
