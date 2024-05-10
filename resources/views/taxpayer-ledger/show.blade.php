@extends('layouts.master')

@section('title', 'Taxpayer Ledgers')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
           {{ $locationName }} - {{ $taxTypeName  }}  Account
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
                </tr>
                </thead>
                <tbody>
                @foreach($ledgers as $key => $ledger)
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
                    <td class="px-2 font-weight-bold">{{ number_format($debitSum, 2) }}</td>
                    <td class="px-2 font-weight-bold">{{ number_format($creditSum, 2)   }}</td>
                </tr>
                <tr>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2"></td>
                    <td class="px-2 font-weight-bold"></td>
                    <td class="px-2 font-weight-bold">{{ number_format($debitSum - $creditSum, 2)   }}</td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
@endsection
