@extends('layouts.master')

@section('title', 'Show Debts Management')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-body mt-0 p-2">
            <h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
            <hr>
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">{{ $debt->app_step }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $debt->taxType->name }}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Principal Amount</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->principal_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Penalty</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->penalty, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Interest</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->interest, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Total Amount</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->original_total_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                    <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->outstanding_amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Due Date</span>
                    <p class="my-1">{{ $debt->curr_due_date }}</p>
                </div>
            </div>
        </div>
    </div>

    @if (count($recovery_measures))
    <div class="card p-0 m-0 mt-4">
        <div class="card-body mt-0 p-2">
            <h6 class="text-uppercase mt-2 ml-2">Recovery Measures</h6>
            <hr>
            <div class="row m-2 pt-3">

                @foreach ($recovery_measures as $key => $recovery_measure)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Measure Type</span>
                        <p class="my-1">{{ $key+1 }}. {{ $recovery_measure->category->name }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    @endif
  
@endsection
