@extends('layouts.master')

@section('title', 'Lump Sum Payments')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white h-100 d-flex justify-content-between align-items-center rounded-1">
            <div>Lump Sum Payments</div>
        </div>

        <div class="card-body">
            <livewire:returns.return-payment :return="$return" />


            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">Lump Sum Payments</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Filled By</span>
                    <p class="my-1">{{ $return->taxpayer->full_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                    <p class="my-1">{{ $return->financialYear->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $return->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Location</span>
                    <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Total</span>
                    <p class="my-1">{{ $return->currency }}
                        {{ number_format($return->total_amount_due_with_penalties ?? 0, 2) }}</p>
                </div>
            </div>

            <livewire:returns.lump-sum.view-return :return="$return">

        </div>

    </div>
@endsection
