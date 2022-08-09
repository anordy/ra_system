<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filled Return Details</h6>
        <hr>
        <div class="row">
            <div class="col-md-12">
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
                            <td>Total Due <br> <small>Kiasi kinacholipiwa</small> </td>
                            <td> {{ number_format($return->value('total_amount_due_with_penalties'), 2, '.', ',') }}
                                {{ $return->currency }}
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
    </div>
</div>
