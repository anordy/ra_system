@extends('layouts.master')

@section('title')
    Paid Properties Payments
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Paid Properties Payments
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('property-tax.payments.property-tax-payment-table', ['status' => \App\Models\Returns\ReturnStatus::COMPLETE])
        </div>
@endsection