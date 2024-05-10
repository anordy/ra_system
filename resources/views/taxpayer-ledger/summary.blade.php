@extends('layouts.master')

@section('title', 'Taxpayer Ledger - Summary of Accounts')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
           {{ $location->name }} Summary of Accounts
        </div>
        <div class="card-body">
            <table class="table table-sm px-2">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Tax Type</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                    <th>T</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ledgers as $key => $ledger)
                    <tr>
                       <td>{{ $key+1  }}</td>
                        <td class="px-2">{{ $ledger->taxtype->name ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->new_amount ?? 'N/A'}}</td>
                        <td class="px-2">{{ $ledger->total_amount ?? 'N/A' }}</td>
                        <td class="px-2">{{ $ledger->currency  }}</td>
                        <td class="px-2">{{ $ledger->transaction_type  }}</td>
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
