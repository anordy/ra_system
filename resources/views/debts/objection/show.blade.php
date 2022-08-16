@extends('layouts.master')

@section('title','Debt Management')

@section('content')
<div class="card mt-3">
    <div class="card-header">Verified Debts with no Objection</div>
    <div class="card-body">
        
        <div class="row m-2 pt-3">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $objection->taxType->name }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Name</span>
                <p class="my-1">{{ $objection->name }}</p>
            </div>
            {{-- <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Business Location</span>
                <p class="my-1">{{ $objection->location->name }}</p>
            </div> --}}
            {{-- <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Payer</span>
                <p class="my-1">{{ $objection->business->taxpayer->first_name }}</p>
            </div> --}}
            {{-- <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Financial Year</span>
                <p class="my-1">{{ $objection->financialYear->name }}</p>
            </div> --}}
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Principal Amount</span>
                <p class="my-1">{{ $objection->principal_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                <p class="my-1">{{ $objection->penalty_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Interest Amount</span>
                <p class="my-1">{{ $objection->interest_amount }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Penalty Amount</span>
                <p class="my-1">{{ $objection->penalty_amount }}</p>
            </div>
            
        </div>
    </div>
</div>

@endsection