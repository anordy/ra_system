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
                    <strong>Land Lease Payments Report</strong><br>
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
                    <strong>DP Number</strong>
                </th>
                <th class="text-center border">
                    <strong>Name</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Year</strong>
                </th>
                <th class="text-center border">
                    <strong>Payment Month</strong>
                </th>
                <th class="text-center border">
                    <strong>Applicant Category</strong>
                </th>
                <th class="text-center border">
                    <strong>ZRA No/ Zin No.</strong>
                </th>
                <th class="text-center border">
                    <strong>Status</strong>
                </th>
                <th class="text-center border">
                    <strong>Principal Amount (USD)</strong>
                </th>
                <th class="text-center border">
                    <strong>total Amount (USD)</strong>
                </th>
                <th class="text-center border">
                    <strong>Total Penalties (USD)</strong>
                </th>
                <th class="text-center border">
                    <strong>Outstanding Amount (USD)</strong>
                </th>
                <th class="text-center border">
                    <strong>Ward</strong>
                </th>
                <th class="text-center border">
                    <strong>Phone</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leasePayments as $index => $leasePayment)
            <tr>
                <td class="text-center border">
                    {{ $index + 1 }}
                </td>
                <td class="text-center border">
                    {{ $leasePayment->landLease->dp_number ?? '-' }}
                </td>
                <td class="text-center border">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->business->name }} | {{ $leasePayment->landLease->businessLocation->name }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->first_name . ' ' . $leasePayment->landLease->taxpayer->last_name : $leasePayment->landLease->name }}
                    @endif
                </td>
                <td class="text-center border">
                    {{ $leasePayment->financialYear->code ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $leasePayment->landLease->payment_month ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ ucwords($leasePayment->landLease->category) }}
                </td>
                <td class="text-center border">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->zin }}
                    @else
                        {{ $leasePayment->landLease->taxpayer->reference_no }}
                    @endif
                </td>
                <td class="text-center border">
                    @if ($leasePayment->status === \App\Enum\LeaseStatus::IN_ADVANCE_PAYMENT)
                            Paid In Advance
                    @elseif ($leasePayment->status === \App\Enum\LeaseStatus::ON_TIME_PAYMENT)
                            Paid On Time
                    @elseif ($leasePayment->status === \App\Enum\LeaseStatus::LATE_PAYMENT)
                            Paid Late
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATING)
                            Control Number Generating
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATED)
                            Control Number Generated
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATION_FAILED)
                            Control Number Generating Failed
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::PAID_PARTIALLY)
                            Paid Partially
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::PENDING)
                            Pending
                    @elseif($leasePayment->status === \App\Enum\LeaseStatus::DEBT)
                            Debt
                    @endif
                </td>
                <td class="text-center border">
                    {{ round($leasePayment->total_amount, 2) ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ round($leasePayment->total_amount_with_penalties, 2) ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ round($leasePayment->penalty, 2) ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ round($leasePayment->outstanding_amount, 2) ?? '-' }}
                </td>
                <td class="text-center border">
                    {{ $leasePayment->landLease->ward->name ?? '-' }}
                </td>
                <td class="text-center border">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->business->mobile }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->mobile : $leasePayment->landLease->phone }}
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>

</html>
