<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="18" height="50">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                <strong>Land Lease Payments Report</strong><br>
                <strong>From {{ $startDate }} To {{ $endDate }} </strong>
            </th>
        </tr>
    </thead>
</table>
<table>
    <thead>
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
                <strong>Payment Year</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Payment Month</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Applicant Category</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>ZRB No/ Zin No.</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Status</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Principal Amount (USD)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>total Amount (USD)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Total Penalties (USD)</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Outstanding Amount (USD)</strong>
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
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Phone</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Email</strong>
            </th>
            <th style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                <strong>Address</strong>
            </th>  
        </tr>
    </thead>
    <tbody>
        @foreach ($leasePayments as $index => $leasePayment)
            <tr>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $index + 1 }}
                </td>
                
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->created_at->format('d/m/Y') ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->landLease->dp_number ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->business->name }} | {{ $leasePayment->landLease->businessLocation->name }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->first_name . ' ' . $leasePayment->landLease->taxpayer->last_name : $leasePayment->landLease->name }}
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->financialYear->code ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->landLease->payment_month ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ ucwords($leasePayment->landLease->category) }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->zin }}
                    @else
                        {{ $leasePayment->landLease->taxpayer->reference_no }}
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
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
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ round($leasePayment->total_amount, 2) ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ round($leasePayment->total_amount_with_penalties, 2) ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ round($leasePayment->penalty, 2) ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ round($leasePayment->outstanding_amount, 2) ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->landLease->region->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->landLease->district->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    {{ $leasePayment->landLease->ward->name ?? '-' }}
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->business->mobile }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->mobile : $leasePayment->landLease->phone }}
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->business->email }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->email : $leasePayment->landLease->email }}
                    @endif
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($leasePayment->landLease->category == 'business')
                        {{ $leasePayment->landLease->businessLocation->physical_address ?? '' }}
                    @else
                        {{ $leasePayment->landLease->is_registered == 1 ? $leasePayment->landLease->taxpayer->physical_address : $leasePayment->landLease->address }}
                    @endif
                </td>   
            </tr>
        @endforeach
    </tbody>
</table>

</html>
