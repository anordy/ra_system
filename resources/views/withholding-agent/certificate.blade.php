<!DOCTYPE html>
<html>

<head>
    <title>Hi</title>
    <style>
        body {
            background-image: url("{{ public_path()}}/images/certificate/withholding_agent.jpg");
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
    </style>
</head>

<body>

    <span class="taxpayerName">{{ $wa_responsible_person->taxpayer->full_name ?? '' }}</span>
    <span class="witholdingAgentNumber">{{ $wa_responsible_person->withholdingAgent->wa_number ?? '' }}</span>

   
</body>

</html>
