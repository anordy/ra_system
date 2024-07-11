@extends('layouts.master')

@section('title', 'View Offence')

@section('content')

    <div class="row m-2 pt-3">
        <div class="col-md-12">
            <livewire:debt.offence.bill-payment :payment="$offence"/>
        </div>
    </div>

    <div class="card rounded-0 mt-3">
        <div class="card-header bg-white">
            Offence Details
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{$offence->status}}
                            </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Debtor Name</span>
                    <p class="my-1">{{$offence->name}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Debtor Mobile</span>
                    <p class="my-1">{{$offence->mobile}}</p>
                </div>

                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount</span>
                    <p class="my-1">{{$offence->amount}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">{{$offence->currency}}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax type</span>
                    <p class="my-1">{{$offence->taxTypes->name}}</p>
                </div>
            </div>

            <div class="row d-none">
                <livewire:debt.offence.approve-offence></livewire:debt.offence.approve-offence>
            </div>
        </div>
    </div>

@endsection

