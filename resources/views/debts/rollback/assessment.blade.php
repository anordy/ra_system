@extends('layouts.master')

@section('title', 'View Debt')

@section('content')
    <div class="card">
        <div class="card-body">
            <div>
                <h6 class="text-uppercase mt-2 ml-2">Rollback Penalty & Interest for {{ $assessment->taxtype->name ?? '' }}
                    Debt
                <h6> <hr>
            </div>

            @if (!$assessment->rollback)
                <livewire:debt.rollback.rollback-assessment assessment_id="{{ encrypt($assessment->id) }}" />

            <div class="row m-2 pt-3">
                <div class="col-md-12 mb-2">
                    <span class="font-weight-bold text-uppercase">Rollback Notice</span>
                    <p class="my-1">* By clicking the "Rollback Penalty & Interest" button the last incremented penalty &
                        Interest ({{ $assessment->penalties->last()->financial_month_name }}) will be removed from
                        this debt</p>
                    <p class="my-1">* Please make sure the payment for this debt has already been cleared</p>
                    <p class="my-1">* This action is irreversible</p>
                </div>
            </div>
            @endif

            <div class="row mx-4">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Tax Amount</th>
                            <th>Late Filing Amount</th>
                            <th>Late Payment Amount</th>
                            <th>Interest Rate</th>
                            <th>Interest Amount</th>
                            <th>Payable Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (count($assessment->penalties) > 0)
                            @foreach ($assessment->penalties as $penalty)
                                <tr>
                                    <td>{{ $penalty['financial_month_name'] ?? $penalty['return_quater'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }}</td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }}</td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }}</td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }}</td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No penalties for this debt.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="mx-4 pt-4">
                @include('debts.assessments.details', ['assessment' => $assessment])
            </div>

        </div>
    </div>

@endsection
