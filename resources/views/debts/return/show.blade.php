@extends('layouts.master')

@section('title','Debt Management')

@section('content')
<div class="card mt-3">
    <div class="card-header">Return Debts</div>
    <div class="card-body">
        
        <div class="row m-2 pt-3">
        
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Payer Name</span>
                <p class="my-1">{{ $return->business->taxpayer->first_name.' '. $return->business->taxpayer->middle_name.' '.$return->business->taxpayer->last_name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Business Name</span>
                <p class="my-1">{{ $return->business->name }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Business Location</span>
                <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Tax Type</span>
                <p class="my-1">{{ $return->taxtype->name }}</p>
            </div>
            
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Mobile</span>
                <p class="my-1">{{ $return->business->mobile }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Email</span>
                <p class="my-1">{{ $return->business->email }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Business Type</span>
                <p class="my-1">{{ $return->business->business_type }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Filled By</span>
                <p class="my-1">{{ $return->business->taxpayer->first_name.' ' .$return->business->taxpayer->middle_name.' ' .$return->business->taxpayer->last_name}}</p>
            </div>
            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Return Month</span>
                <p class="my-1">{{$return->financialMonth->name}} {{ $return->financialYear->code }}</p>
            </div>

            <div class="col-md-3 mb-3">
                <span class="font-weight-bold text-uppercase">Currency</span>
                <p class="my-1">{{ $return->business->currency->iso ?? 'Head Quarter' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Total Debt</span>
                <p class="my-1">{{ $return->total_amount_due_with_penalties }}</p>
            </div>
            
        </div>
    </div>
</div>

@endsection