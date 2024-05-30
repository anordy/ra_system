@extends('layouts.master')

@section('title')
    PBZ Transactions
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            PBZ Transactions
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Payments</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Reversals</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show m-2">
                    @livewire('payments.p-b-z-payment-filter', ['tablename' => 'p-b-z-payments-table']) <br>
                    <livewire:payments.p-b-z-payments-table status='pending'></livewire:payments.p-b-z-payments-table>
                </div>
                <div id="tab2" class="tab-pane fade m-2">
                    @livewire('payments.p-b-z-payment-filter', ['tablename' => 'p-b-z-reversals-table']) <br>
                    <livewire:payments.p-b-z-reversals-table status='pending'></livewire:payments.p-b-z-reversals-table>
                </div>
            </div>
        </div>
    </div>
@endsection