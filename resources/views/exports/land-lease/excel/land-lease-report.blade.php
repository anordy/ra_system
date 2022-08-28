<html>
<table style="border-collapse:collapse;">
    <thead>
        <tr>
            <th style="text-align:center;" colspan="18" height="50">
                <strong>ZANZIBAR REVENUE BOARD</strong><br>
                <strong>Land Leases</strong><br>
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
                        {{ $landLease->businessLocation->business->name }} | {{ $landLease->businessLocation->name }}
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
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
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
                </td>
                <td style="text-align:center;border-collapse:collapse;border: 1px solid black;">
                    @if ($landLease->category == 'business')
                        {{ $landLease->businessLocation->business->physical_address }}
                    @else
                        {{ $landLease->is_registered == 1 ? $landLease->taxpayer->physical_address : $landLease->address }}
                    @endif
                </td>   
            </tr>
        @endforeach
    </tbody>
</table>

</html>
