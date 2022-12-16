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

        .embed {
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
        }
        .institutionName {
            font-size: 1.15em;
            top: 39%;
        }

        .witholdingAgentNumber {
            font-size: 1.15em;
            top: 70.5%;
        }
        .startDate {
            font-size: 1.15em;
            top: 75%;
        }

        .dateGiven {
            font-size: 1.15em;
            top: 80%;
        }
        .location {
            font-size: 1.15em;
            top: 46.7%;
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

    <span class="embed institutionName">{{ $whagent->institution_name  ?? '' }}</span>
    <span class="embed location">{{ $whagent->institution_place ?? '' }}</span>
    <span class="embed witholdingAgentNumber">{{ $whagent->wa_number ?? '' }}</span>
    <span class="embed startDate">{{ $whagent->latestResponsiblePerson->created_at->toFormattedDateString() ?? '' }}</span>
    <span class="embed dateGiven">{{ date('M d, Y') }}</span>
    <span class="commissioner-signature">
        <img src="{{ public_path()}}/sign/commissioner.png">
    </span>
    <div style="overflow: hidden; position:absolute; top: 83%; left: 44%; background: white; border-radius: 5px; height: 180px; width: 180px; padding: 5px">
        <img class="img-fluid" src="{{ $dataUri }}" style="height: 189px">
    </div>
</body>

</html>
