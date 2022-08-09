<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Declared Sales Analysis</h6>
        <hr>
        <div class="row mx-2">
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
                    @foreach ($returns as $return)
                        <tr>
                            <td>{{ $return['financial_month'] }}</td>
                            <td>{{ $return['total_purchases'] }}</td>
                            <td>{{ $return['input_tax'] }}</td>
                            <td>{{ $return['total_sales'] }}</td>
                            <td>{{ $return['output_vat'] }}</td>
                            <td>{{ $return['tax_paid'] }}</td>
                        </tr>
                    @endforeach
             
                </tbody>

           </table>
        </div>
    </div>
</div>
