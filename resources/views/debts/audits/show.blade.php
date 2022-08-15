@extends('layouts.master')

@section('title','Auditing Debt Management')

@section('content')
<div class="card mt-3">
    <div class="card-header">Auditing Assesment Debts</div>
    <div class="card-body">
        
        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $debt->taxType->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Name</span>
                <p class="my-1">{{ $debt->business->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Location</span>
                <p class="my-1">{{ $debt->location->name }}</p>
            </div>
             <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Payer</span>
                <p class="my-1">{{ $debt->business->taxpayer->first_name }}</p>
            </div> 
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                <p class="my-1">{{ $assesment->principal_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                <p class="my-1">{{ $assesment->penalty_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                <p class="my-1">{{ $assesment->interest_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Total Debt Amount</span>
                <p class="my-1">{{ $assesment->penalty_amount + $assesment->principal_amount + $assesment->interest_amount }}</p>
            </div>
            
        </div>
    </div>
</div>

@endsection