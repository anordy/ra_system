@extends('layouts.master')

@section('title', 'Request for Installment')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Debt Details
        </div>
        <div class="card-body">
           <div class="row">
               <div class="col-md-4 mb-3">
                   <span class="font-weight-bold text-uppercase">Status</span>
                   <p class="my-1">{{ $debt->app_step }}</p>
               </div>
               <div class="col-md-4 mb-3">
                   <span class="font-weight-bold text-uppercase">Tax Type</span>
                   <p class="my-1">{{ $debt->taxType->name }}</p>
               </div>
               <div class="col-md-4 mb-3">
                   <span class="font-weight-bold text-uppercase">Due Date</span>
                   <p class="my-1">{{ $debt->last_due_date }}</p>
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
                   <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
                   <p class="my-1">{{ $debt->currency }}. {{ number_format($debt->outstanding_amount, 2) }}</p>
               </div>
               <div class="col-md-4 mb-3">
                   <span class="font-weight-bold text-uppercase">Payment Status</span>
                   {{--                    <p class="my-1">{{ $debt->debt->status }}</p>--}}
               </div>
           </div>
        </div>
    </div>
    <livewire:installment.new-installment-request :debt="$debt" />
@endsection