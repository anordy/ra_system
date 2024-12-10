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
                        <td class="px-2 font-weight-bold"></td>
                        <td class="px-2 font-weight-bold"></td>
                        <td class="px-2 font-weight-bold">{{ number_format($tzsOpeningFigures['debit'] - $tzsOpeningFigures['credit'], 2)   }}</td>
                    </tr>
                @endif
                @php
                    $balanceTZS = 0;
                    if (count($tzsOpeningFigures) > 0) {
                        $balanceTZS += $tzsOpeningFigures['debit'] - $tzsOpeningFigures['credit'];
                    }
                @endphp
                @foreach($ledgers['TZS'] as $key => $ledger)
                    <tr>
                        <td class="px-2">
                            @if($ledger->transaction_type === \App\Enum\TransactionType::DEBIT)
                                {{ $ledger->financialMonth->name ?? 'N/A' }},  {{ $ledger->financialMonth->year->code ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-2">{{ $ledger->transaction_date ? \Carbon\Carbon::create($ledger->transaction_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-2">{{ getSourceName($ledger->source_type) ?? 'N/A' }} - {{ $ledger->taxtype->name ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->debit_no ?? '' }}</td>
                        <td class="px-2">{{ $ledger->currency  }}</td>
                        <td class="px-2">{{ number_format($ledger->principal_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->interest_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->penalty_amount, 2)  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? number_format($ledger->total_amount, 2) : 0  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::CREDIT ? number_format($ledger->total_amount, 2) : 0   }}</td>
                        @php
                            if ($ledger->transaction_type === \App\Enum\TransactionType::DEBIT) {
                                $balanceTZS += $ledger->total_amount;
                            } elseif ($ledger->transaction_type === \App\Enum\TransactionType::CREDIT) {
                                $balanceTZS -= $ledger->total_amount;
                            }
                        @endphp
                        <td class="px-2">{{ number_format($balanceTZS, 2) }}</td>
                    </tr>
                @endforeach
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
                        <td class="px-2 font-weight-bold"></td>
                        <td class="px-2 font-weight-bold"></td>
                        <td class="px-2 font-weight-bold">{{ number_format($usdOpeningFigures['debit'] - $usdOpeningFigures['credit'], 2)   }}</td>
                    </tr>
                @endif
                @php
                    $balanceUSD = 0;
                     if (count($usdOpeningFigures) > 0) {
                        $balanceUSD += $usdOpeningFigures['debit'] - $usdOpeningFigures['credit'];
                    }
                @endphp
                @foreach($ledgers['USD'] as $key => $ledger)
                    <tr>
                        <td class="px-2">
                            @if($ledger->transaction_type === \App\Enum\TransactionType::DEBIT)
                                {{ $ledger->financialMonth->name ?? 'N/A' }},  {{ $ledger->financialMonth->year->code ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-2">{{ $ledger->transaction_date ? \Carbon\Carbon::create($ledger->transaction_date)->format('d M Y') : 'N/A' }}</td>
                        <td class="px-2">{{ getSourceName($ledger->source_type) ?? 'N/A' }} - {{ $ledger->taxtype->name ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->debit_no ?? '' }}</td>
                        <td class="px-2">{{ $ledger->currency }}</td>
                        <td class="px-2">{{ number_format($ledger->principal_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->interest_amount, 2)  }}</td>
                        <td class="px-2">{{ number_format($ledger->penalty_amount, 2)  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::DEBIT ? number_format($ledger->total_amount, 2) : 0  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type === \App\Enum\TransactionType::CREDIT ? number_format($ledger->total_amount, 2) : 0   }}</td>
                        @php
                            if ($ledger->transaction_type === \App\Enum\TransactionType::DEBIT) {
                                $balanceUSD += $ledger->total_amount;
                            } elseif ($ledger->transaction_type === \App\Enum\TransactionType::CREDIT) {
                                $balanceUSD -= $ledger->total_amount;
                            }
                        @endphp
                        <td class="px-2">{{ number_format($balanceUSD, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endif