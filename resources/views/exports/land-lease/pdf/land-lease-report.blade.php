<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            margin: 15px;
            opacity: 0.1;
        }

        thead {
            text-align: center
        }

        .tableHead {
            background-color: rgb(182, 193, 208);
            color: rgb(0, 0, 0);

        }

        tbody tr:nth-child(odd) {
            background-color: #fff;
        }

        tbody tr:nth-child(even) {
            background-color: #ddd;
        }

        .total {
            background-color: rgb(201, 201, 201);
            color: rgb(0, 0, 0);
            font-weight: bold;
        }

        .zrb {
            /* background-color: rgb(182, 193, 208); */
            color: rgb(19, 19, 19);
            font-weight: bold;
            font-size: 30px;
        }

        .table {
            border: 1px solid black;
            width: 100%;
            border-collapse: collapse;
        }

        .border {
            border-collapse: collapse;
            border: 1px solid black;
        }

        .text-center {
            text-align: center;
        }

        .font-size-8 {
            font-size: 8pt;
        }

        .top-table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
</head>

<body class="font-size-8">
    <table class="top-table">
        <thead>
            <tr>
                <th class="text-center" colspan="18" >
                    <strong class="zrb">ZANZIBAR REVENUE AUTHORITY</strong><br>
                    <strong>Land Leases Report</strong><br>
                    <strong>From {{ $startDate }} To {{ $endDate }} </strong>
                </th>
            </tr>
        </thead>
    </table>
    <table class="table">
        <thead class="tableHead">
            <tr>
                <th class="text-center border">
                    <strong>S/N</strong>
                </th>
                <th class="text-center border">
                    <strong>Registered Date</strong>
                </th>
                <th class="text-center border">
                    <strong>DP Number</strong>
                </th>
                <th class="text-center border">
                    <strong>Name</strong>
                </th>
                <th class="text-center border">
                    <strong>Applicant Category</strong>
                </th>
                <th class="text-center border">
                    <strong>ZRA No/ Zin No.</strong>
                </th>
                <th class="text-center border">
                    <strong>Applicant Type</strong>
                </th>
                <th class="text-center border">
                    <strong>Commence Date</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Month</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Amount (USD)</strong>
                </th>
                <th class="text-center border">
                    <strong>Review Schedule</strong>
                </th>
                <th class="text-center border">
                    <strong>Valid Period Term</strong>
                </th>
                <th class="text-center border">
                    <strong>Region</strong>
                </th>
                <th class="text-center border">
                    <strong>District</strong>
                </th>
                <th class="text-center border">
                    <strong>Ward</strong>
                </th>
                {{-- <th class="text-center border">
                    <strong>Phone</strong>
                </th>
                <th class="text-center border">
                    <strong>Email</strong>
                </th> --}}
                <th class="text-center border">
                    <strong>Address</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($landLeases as $index => $landLease)
                <tr>
                    <td class="text-center border">
                        {{ $index + 1 }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->created_at->format('d/m/Y') ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->dp_number ?? '-' }}
                    </td>
                    <td class="text-center border">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->name }} |
                            {{ $landLease->businessLocation->name }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->first_name . ' ' . $landLease->taxpayer->last_name : $landLease->name }}
                        @endif
                    </td>
                    <td class="text-center border">
                        {{ ucwords($landLease->category) }}
                    </td>
                    <td class="text-center border">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->zin }}
                        @else
                            {{ $landLease->taxpayer->reference_no }}
                        @endif
                    </td>
                    <td class="text-center border">
                        {{ $landLease->is_registered == 1 ? 'Registered' : 'Unregistered' }}
                    </td>
                    <td class="text-center border">
                        {{ date('d/m/Y', strtotime($landLease->commence_date)) }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->payment_month ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->payment_amount ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->review_schedule ?? '-' }} years
                    </td>
                    <td class="text-center border">
                        {{ $landLease->valid_period_term ?? '-' }} years
                    </td>
                    <td class="text-center border">
                        {{ $landLease->region->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->district->name ?? '-' }}
                    </td>
                    <td class="text-center border">
                        {{ $landLease->ward->name ?? '-' }}
                    </td>
                    {{-- <td class="text-center border">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->mobile }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->mobile : $landLease->phone }}
                        @endif
                    </td>
                    <td class="text-center border">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->email }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->email : $landLease->email }}
                        @endif
                    </td> --}}
                    <td class="text-center border">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->physical_address ?? '' }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->physical_address : $landLease->address }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
