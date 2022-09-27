<table style="border-collapse:collapse; width:100%">
    <thead>
    <tr>
        <th>
            <p style="margin-bottom: 0px; margin-top: 25px; text-align: left; font-size: 18px; text-transform: uppercase">Return Details</p>
        </th>
    </tr>
    </thead>
</table>
<table class="tbl-bordered tbl-p-6" style="width: 100%; margin-top: 10px;">
    <tbody>
    <tr>
        <td>Annual Estimates<br> <span>Makisio Ya Mwaka</span> </td>
        <td>
            {{ number_format($return->assignedPayments->annual_estimate, 2, '.', ',') }}
            <b>{{ $return->currency }}</b>
        </td>
    </tr>
    <tr>
        <td>Payment Quaters Per Year<br> <span> Awamu Zinazolipwa Kwa Mwaka </span> </td>
        <td>{{ $return->assignedPayments->payment_quarters }}</td>
    </tr>
    @if ($return->penalties)
        <tr>
            <td>Payment for <br> <span> Malipo kwa ajili ya </span> </td>
            <td>{{ getNumberOrdinal($return->quarter) }} Quarter plus Penalties for late payment <br>
                <span>Awamu ya {{ $return->quarter }} na Adhabu ya kuchelewesha malipo</span>
            </td>
        </tr>
    @else
        <tr>
            <td>Quater of <br> <span>Awamu ya</span> </td>
            <td> {{ $return->installment }} </td>
        </tr>
    @endif
    <tr>
        <td>Amount Paid on every Quater <br> <span>Kiasi kinacholipiwa kwa kila Awamu</span> </td>
        <td>
            {{ number_format($return->total_amount_due, 2, '.', ',') }}
            <b>{{ $return->currency }}</b>
        </td>
    </tr>
    @if ($return->penalties)
        <tr>
            <td>Late payments penalities <br> <span>Adhabu ya kuchelewesha malipo</span> </td>
            <td>
                {{ number_format($return->total_amount_due_with_penalties - $return->total_amount_due, 2, '.', ',') }}
                <b>{{ $return->currency }}</b>
            </td>
        </tr>
    @endif
    <tr>
        <td>Status <br> <span> Hatua</span> </td>
        <td>{{ strtoupper($return->status) }}</td>
    </tr>
    </tbody>
</table>