@if ($withoutPurchases)

    @if ($returnTypeTable == App\Models\TaxType::EXCISE_DUTY_BFO)
        @include('investigation.approval.return-details.bfo')
    @elseif($returnTypeTable == App\Models\TaxType::EXCISE_DUTY_MNO)
        @include('investigation.approval.return-details.mno')
    @elseif ($returnTypeTable == App\Models\TaxType::ELECTRONIC_MONEY_TRANSACTION)
        @include('investigation.approval.return-details.emtransaction')
    @elseif ($returnTypeTable == App\Models\TaxType::MOBILE_MONEY_TRANSFER)
        @include('investigation.approval.return-details.mmtransfer')
    @elseif ($returnTypeTable == App\Models\TaxType::PETROLEUM)
        @include('investigation.approval.return-details.petroleum')
    @endif
    
@else
    <div class="card">
        <div class="card-body">
            <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
            <hr>
            <div class="row mx-2">

                @foreach ($returns as $year => $return)
                    <strong>{{ $year }}</strong>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Declared Purchases</th>
                                <th>Input TAX</th>
                                <th>Declared Sales</th>
                                <th>Output VAT</th>
                                <th>Tax Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($return as $item)
                                <tr>
                                    <td>{{ $item['financial_month'] }}</td>
                                    <td>{{ $item['total_purchases'] }}</td>
                                    <td>{{ $item['input_tax'] }}</td>
                                    <td>{{ $item['total_sales'] }}</td>
                                    <td>{{ $item['output_vat'] }}</td>
                                    <td>{{ $item['tax_paid'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>TOTAL</td>
                                <th>{{ $return->sum('total_purchases') }}</th>
                                <th>{{ $return->sum('input_tax') }}</th>
                                <th>{{ $return->sum('total_sales') }}</th>
                                <th>{{ $return->sum('output_vat') }}</th>
                                <th>{{ $return->sum('tax_paid') }}</th>
                            </tr>
                        </tfoot>

                    </table>
                @endforeach

            </div>
        </div>
    </div>

@endif
