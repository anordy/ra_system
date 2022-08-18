<div>
    <div class="p-3">

        @if ($penalties)

            <div class="col-md-12 px-0">
                <h6>Penalities for late payments</h6>
                <table class="table table-bordered normal-text">
                    <thead>
                        <tr>
                            <th>Quater of</th>
                            <th>Tax Amount</th>
                            <th>Late Payment Amount</th>
                            <th>Interest Amount</th>
                            <th>Tax Amount with Penalty </th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (is_array($penalties) && count($penalties))
                            @foreach ($penalties as $penalty)
                                <tr>
                                    <td>{{ $penalty['return_quater'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }}</td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}</td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    No penalties for this return.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

            </div>
        @endif

        <table class="table table-bordered ">
            <tbody>
                <tr>
                    <td>Annual Estimates<br> <small>Maksio Ya Mwaka</small> </td>
                    <td> {{ number_format($return->assignedPayments->annual_estimate, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Payment Quaters Per Year<br> <small> Awamu Zinazolipwa Kwa Mwaka </small> </td>
                    <td>{{ $return->assignedPayments->payment_quarters }}</td>
                </tr>
                @if ($penalties)
                    <tr>
                        <td>Payment for <br> <small> Malipo kwa ajili ya </small> </td>
                        <td>
                            {{ getNumberOrdinal($return->quarter) }}
                            Quater plus Penalties for late payment <br>
                            <small>Awamu ya {{ $return->quarter }} na Adhabu ya kuchelewesha
                                malipo</small>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>Quater <br> <small> Awamu</small> </td>
                        <td> {{ $return->installment }} </td>
                    </tr>
                @endif
                <tr>
                    <td>Amount Paid on every Quater <br> <small>Kiasi kinacholipiwa kwa kila Awamu</small> </td>
                    <td> {{ number_format($return->total_amount_due, 2, '.', ',') }}</td>
                </tr>
                @if ($penalties)
                    <tr>
                        <td>Late payments penalities <br> <small>Adhabu ya kuchelewesha malipo</small> </td>
                        <td> {{ number_format($return->total_amount_due_with_penalties - $return->total_amount_due, 2, '.', ',') }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>Status <br> <small> Hatua</small> </td>
                    <td>{{ $return->status }}</td>
                </tr>
            </tbody>
        </table>
        <x-bill-structure :bill="$return->bill" />


    </div>
</div>
