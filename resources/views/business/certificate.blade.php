<!DOCTYPE html>
<html>

<head>
    <title>Certificate of Registration - {{ $business->name }}</title>
    <style>
        body {
            background-image: url("{{ public_path()}}/images/certificate/business_reg.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            margin: -70px;
        }
        .embed-text {
            position: absolute;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
            width: 100%;
            padding-left: 70px;
            padding-right: 70px;
        }
        .business-name {
            font-size: 1.15em;
            top: 33.5%;
        }
        .taxpayer-name {
            font-size: 1.5em;
            top: 41%;
        }
        .reg-no {
            font-size: 1.5em;
            top: 53%;
        }
        .tax-types {
            font-size: 1.1em;
            top: 61%;
        }
        .location {
            font-size: 1.2em;
            top: 72%;
        }
        .commencing-date {
            font-size: 1.2em;
            top: 80%;
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
            top: 40%;
        }
    </style>
</head>

<body>
<span class="embed-text business-name">{{ $business->name ?? '' }}</span>
<span class="embed-text taxpayer-name">{{ $business->tin ?? '' }}</span>
<span class="embed-text reg-no">{{ $business->reg_no ?? '' }}</span>
<span class="embed-text tax-types">
        @foreach($business->taxTypes as $type)
        @if ($loop->last)
            AND {{ $type->name }}.
        @elseif($loop->remaining > 1)
            {{ $type->name }},
        @else
            {{ $type->name }}
        @endif
    @endforeach
    </span>
<span class="embed-text location">
        {{ $business->location->street }}, {{ $business->location->district->name }}, {{ $business->location->region->name }}
    </span>
<span class="embed-text commencing-date">
        {{ $business->date_of_commencing->toFormattedDateString() }}
    </span>
</body>

</html>
