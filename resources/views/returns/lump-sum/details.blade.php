<div>

    <div class="p-3">
        @php($penalties = $return->penalties)

        @if ($penalties->count() > 0)

            <div class="col-md-12 px-0"><br><br>
                <h6>Penalties for late payments</h6>
                <table class="table table-bordered normal-text">
                    <thead>
                        <tr>
                            <th>Quarter of</th>
                            <th>Tax Amount</th>
                            <th>Late Payment Amount</th>
                            <th>Interest Rate</th>
                            <th>Interest Amount</th>
                            <th>Tax Amount with Penalty </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($penalties as $penalty)
                            <tr>
                                <td>{{ $penalty['return_quater'] }}</td>
                                <td>{{ number_format($penalty['tax_amount'], 2) }}</td>
                                <td>{{ number_format($penalty['late_payment'], 2) }}</td>
                                <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                <td>{{ number_format($penalty['rate_amount'], 2) }}</td>
                                <td>{{ number_format($penalty['penalty_amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @endif

        <table class="table table-bordered ">
            <tbody>
                <tr>
                    <td>Annual Estimates<br> <small>Makisio Ya Mwaka</small> </td>
                    <td> {{ number_format($return->assignedPayments->annual_estimate, 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Payment Quarters per year <br> <small> Awamu zinazolipwa kwa mwaka</small> </td>
                    <td>{{ $return->assignedPayments->payment_quarters }}</td>
                </tr>
                @if ($penalties->count() > 0)
                    <tr>
                        <td>Payment For <br> <small> Malipo Kwa Ajili Ya</small> </td>
                        <td>
                            {{ getNumberOrdinal($return->quarter) }} Quarter plus Late Payments Penalties <br>
                            <small>Awamu ya {{ $return->quarter }} na Adhabu ya kuchelewesha malipo
                            </small>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>Current Quarter <br> <small> Awamu ya sasa hivi</small> </td>
                        <td> {{ $return->quarter }} </td>
                    </tr>
                @endif
                <tr>
                    <td>Amount Due on this Quarter <br> <small>Kiasi kinacholipiwa kwa Awamu Hii</small> </td>
                    <td> {{ number_format($return->total_amount_due, 2, '.', ',') }}</td>
                </tr>
                @if ($penalties->count() > 0)
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


    </div>
</div>
