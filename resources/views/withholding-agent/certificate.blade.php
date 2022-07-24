<!DOCTYPE html>
<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/certificate/withholding_agent.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
        }
        .taxpayerName {
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
            font-size: 1.2em;
            top: 46.5%;
            left: 45%;
            text-transform: uppercase;
            font-weight: bold
        }
    </style>
</head>

<body>

    <p class="watermark">Online Copy</p>
    <span class="taxpayerName">{{ $wa_responsible_person->taxpayer->full_name ?? '' }}</span>
    <span class="location">{{ $wa_responsible_person->taxpayer->location ?? '' }}</span>
    <span class="witholdingAgentNumber">{{ $wa_responsible_person->withholdingAgent->wa_number ?? '' }}</span>
    <span class="startDate">{{ $wa_responsible_person->created_at->toFormattedDateString() ?? '' }}</span>
    <span class="dateGiven">{{ date('M d, Y') }}</span>
</body>

</html>
