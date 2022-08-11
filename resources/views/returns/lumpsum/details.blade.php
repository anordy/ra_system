<div>

    <div class="p-3">
        @php($penalties = $return->penalties)

        @if ($penalties->count() > 0)

            <div class="col-md-12 px-0"><br><br>
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
                        @foreach ($penalties as $penalty)
                            <tr>
                                <td>{{ $penalty['returnMonth'] }}</td>
                                <td>{{ number_format($penalty['taxAmount'], 2) }}</td>
                                <td>{{ number_format($penalty['latePaymentAmount'], 2) }}</td>
                                <td>{{ number_format($penalty['interestAmount'], 2) }}</td>
                                <td>{{ number_format($penalty['penaltyAmount'], 2) }}</td>
                            </tr>
                        @endforeach
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
                    <td>Payable after every Months <br> <small> Inalipwa Kila baada ya miezi</small> </td>
                    <td>{{ $return->assignedPayments->payment_quarters }}</td>
                </tr>
                @if ($penalties->count() > 0)
                    <tr>
                        <td>Payment For <br> <small> Malipo Kwa Ajili Ya</small> </td>
                        <td> Late Payments Penalties <br> <small>Adhabu ya kuchelewesha malipo</small> </td>
                    </tr>
                @else
                    <tr>
                        <td>Quater <br> <small> Awamu</small> </td>
                        <td> {{ $return->quarter }} </td>
                    </tr>
                @endif
                <tr>
                    <td>Amount Due on this Quater <br> <small>Kiasi kinacholipiwa kwa Awamu Hii</small> </td>
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
