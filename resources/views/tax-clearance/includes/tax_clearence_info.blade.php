<div class="pt-3">
    @if($ledgers['TZS'])
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold text-uppercase">
                TZS Summary of Accounts
            </div>
            <div class="card-body">
                <table class="table table-sm px-2">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tax Type</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th>Tax Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ledgers['TZS'] as $key => $ledger)
                        <tr>
                            <td>{{ $key+1  }}</td>
                            <td class="px-2">{{ $ledger->tax_type_name ?? 'N/A' }}</td>
                            <td class="px-2">{{ number_format($ledger->total_debit_amount, 2) ?? 'N/A'}}</td>
                            <td class="px-2">{{ number_format($ledger->total_credit_amount, 2) ?? 'N/A' }}</td>
                            <td class="px-2">{{ number_format($ledger->total_credit_amount - $ledger->total_debit_amount, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="px-2"></td>
                        <td class="px-2">@if(count($ledgers['TZS']) <= 0) N/A @endif</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['debit'], 2) }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['credit'], 2)   }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['credit'] - $summations['TZS']['debit'], 2)   }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    @endif
    @if($ledgers['USD'])
        <div class="card rounded-0">
            <div class="card-header bg-white font-weight-bold text-uppercase">
                USD Summary of Accounts
            </div>
            <div class="card-body">
                <table class="table table-sm px-2">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tax Type</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th>Tax Balance</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ledgers['USD'] as $key => $ledger)
                        <tr>
                            <td>{{ $key+1  }}</td>
                            <td class="px-2">{{ $ledger->tax_type_name ?? 'N/A' }}</td>
                            <td class="px-2">{{ number_format($ledger->total_debit_amount, 2) ?? 'N/A'}}</td>
                            <td class="px-2">{{ number_format($ledger->total_credit_amount, 2) ?? 'N/A' }}</td>
                            <td class="px-2">{{ number_format($ledger->total_credit_amount - $ledger->total_debit_amount, 2) ?? 'N/A'  }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="px-2"></td>
                        <td class="px-2">N/A</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['debit'], 2) }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['credit'], 2)   }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['credit'] - $summations['USD']['debit'], 2)   }}</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
    @endif

</div>
