@extends('layouts.master')

@section('title', 'Payment Summary')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Manage Payment
        </div>
        <div class="card-body">
            <livewire:payments.bill-action></livewire:payments.bill-action>
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Pending Payments
        </div>
        <div class="card-body">
            <livewire:payments.pending-payments-table />
        </div>
    </div>
@endsection
