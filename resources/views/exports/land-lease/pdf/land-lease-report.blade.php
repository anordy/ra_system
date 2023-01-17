<html>

<head>
    <title></title>
    <style>
        body {
            background-image: url("{{ public_path() }}/images/logo.jpg");
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
    </style>
</head>

<body style="font-size: 8pt">
    <table style="border-collapse:collapse; width:100%">
        <thead>
            <tr>
                <th style="text-align:center;" colspan="18" >
                    <strong class="zrb">ZANZIBAR REVENUE BOARD</strong><br>
                    <strong>Land Leases Report</strong><br>
                    <strong>From {{ $startDate }} To {{ $endDate }} </strong>
                </th>
            </tr>
        </thead>
    </table>
    <table class="table">
        <thead class="tableHead">
            <tr>
                <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                    <strong>S/N</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Registered Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>DP Number</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Name</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Applicant Category</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>ZRB No/ Zin No.</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Applicant Type</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Commence Date</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payment Month</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Payment Amount (USD)</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Review Schedule</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Valid Period Term</strong>
                </th>
                <th style="text-align:center; border-collapse:collapse;border: 1px solid black;">
                    <strong>Region</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>District</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Ward</strong>
                </th>
                {{-- <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Phone</strong>
                </th>
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Email</strong>
                </th> --}}
                <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    <strong>Address</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($landLeases as $index => $landLease)
                <tr>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $index + 1 }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->created_at->format('d/m/Y') ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->dp_number ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->name }} |
                            {{ $landLease->businessLocation->name }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->first_name . ' ' . $landLease->taxpayer->last_name : $landLease->name }}
                        @endif
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ ucwords($landLease->category) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->zin }}
                        @else
                            {{ $landLease->taxpayer->reference_no }}
                        @endif
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->is_registered == 1 ? 'Registered' : 'Unregistered' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ date('d/m/Y', strtotime($landLease->commence_date)) }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->payment_month ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->payment_amount ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->review_schedule ?? '-' }} years
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->valid_period_term ?? '-' }} years
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->region->name ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->district->name ?? '-' }}
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        {{ $landLease->ward->name ?? '-' }}
                    </td>
                    {{-- <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->mobile }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->mobile : $landLease->phone }}
                        @endif
                    </td>
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                        @if ($landLease->category == 'business')
                            {{ $landLease->businessLocation->business->email }}
                        @else
                            {{ $landLease->is_registered == 1 ? $landLease->taxpayer->email : $landLease->email }}
                        @endif
                    </td> --}}
                    <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
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
