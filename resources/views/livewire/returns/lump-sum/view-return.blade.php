<div>
    <div class="p-3">
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

                <tr>
                    <td>Quater <br> <small> Awamu</small> </td>
                    <td> {{ $return->value('quarter') }} </td>
                </tr>
                <tr>
                    <td>Amount Due on this Quater minus penalities <br> <small>Kiasi kinacholipiwa kwa Awamu Hii bila
                            adhabu</small> </td>
                    <td> {{ number_format($return->value('total_amount_due'), 2, '.', ',') }}</td>
                </tr>
                <tr>
                    <td>Late payments penalities <br> <small>Adhabu ya kuchelewesha malipo</small> </td>
                    <td> {{ number_format($return->bill->amount - $return->value('total_amount_due'), 2, '.', ',') }}
                    </td>
                </tr>
                <tr>
                    <td>Status <br> <small> Hatua</small> </td>
                    <td>{{ $return->value('status') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
