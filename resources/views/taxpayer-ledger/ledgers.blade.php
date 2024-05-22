@if($ledgers['TZS'])
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $locationName }} - {{ $taxTypeName  }} TZS  Account
        </div>
        <div class="card-body">
            <table class="table table-sm px-2">
                <thead>
                <tr>
                    <th>Financial Month</th>
                    <th>Transaction Date</th>
                    <th>Transaction Type</th>
                    <th>Debit No</th>
                    <th>Currency</th>
                    <th>Principal</th>
                    <th>Interest</th>
                    <th>Penalty</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
                </thead>
                <tbody>
                @if(count($tzsOpeningFigures) > 0)
                    <tr>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2 font-weight-bold">{{ number_format($tzsOpeningFigures['debit'], 2) }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($tzsOpeningFigures['credit'], 2)   }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($tzsOpeningFigures['credit'] - $tzsOpeningFigures['debit'], 2)   }}</td>
                    </tr>
                @endif
                @foreach($ledgers['TZS'] as $key => $ledger)
                    <tr>
                        <td class="px-2">
                            @if($ledger->transaction_type === \App\Enum\TransactionType::DEBIT)
                                {{ $ledger->financialMonth->name ?? 'N/A' }},  {{ $ledger->financialMonth->year->code ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-2">{{ $ledger->transaction_date ? \Carbon\Carbon::create($ledger->transaction_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-2">{{ getSourceName($ledger->source_type) ?? 'N/A' }} - {{ $ledger->taxtype->name ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? 'D'. str_pad($ledger->id, 6, "0", STR_PAD_LEFT) : '' }}</td>
                        <td class="px-2">{{ $ledger->currency  }}</td>
                        <td class="px-2">{{ number_format($ledger->principal_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->interest_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->penalty_amount, 2)  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? number_format($ledger->total_amount, 2) : 0  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::CREDIT ? number_format($ledger->total_amount, 2) : 0   }}</td>
                        <td class="px-2">
                            {{ $ledger->transaction_type === \App\Enum\TransactionType::CREDIT ? $ledger->total_amount : 0 - ($ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? $ledger->total_amount : 0)  }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['debit'], 2) }}</td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['credit'], 2)   }}</td>
                </tr>
                <tr>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2 font-weight-bold"></td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['TZS']['debit'] - $summations['TZS']['credit'], 2)   }}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>

@endif

@if($ledgers['USD'])
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $locationName }} - {{ $taxTypeName  }} USD  Account
        </div>
        <div class="card-body">
            <table class="table table-sm px-2">
                <thead>
                <tr>
                    <th>Financial Month</th>
                    <th>Transaction Date</th>
                    <th>Transaction Type</th>
                    <th>Debit No</th>
                    <th>Currency</th>
                    <th>Principal</th>
                    <th>Interest</th>
                    <th>Penalty</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
                </thead>
                <tbody>
                @if(count($usdOpeningFigures) > 0)
                    <tr>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2"></td>
                        <td class="px-2 font-weight-bold">{{ number_format($usdOpeningFigures['debit'], 2) }}</td>
                        <td class="px-2 font-weight-bold">{{ number_format($usdOpeningFigures['credit'], 2)   }}</td>
                    </tr>
                @endif
                @foreach($ledgers['USD'] as $key => $ledger)
                    <tr>
                        <td class="px-2">
                            @if($ledger->transaction_type === \App\Enum\TransactionType::DEBIT)
                                {{ $ledger->financialMonth->name ?? 'N/A' }},  {{ $ledger->financialMonth->year->code ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-2">{{ $ledger->transaction_date ? \Carbon\Carbon::create($ledger->transaction_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-2">{{ getSourceName($ledger->source_type) ?? 'N/A' }} - {{ $ledger->taxtype->name ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? 'D'. str_pad($ledger->id, 6, "0", STR_PAD_LEFT) : '' }}</td>
                        <td class="px-2">{{ $ledger->currency }}</td>
                        <td class="px-2">{{ number_format($ledger->principal_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->interest_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->penalty_amount, 2)  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? number_format($ledger->total_amount, 2) : 0  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::CREDIT ? number_format($ledger->total_amount, 2) : 0   }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['credit'], 2) }}</td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['debit'], 2)   }}</td>
                </tr>
                <tr>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2 font-weight-bold"></td>
                    <td class="px-2 font-weight-bold">{{ number_format($summations['USD']['debit'] - $summations['USD']['credit'], 2)   }}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>

@endif