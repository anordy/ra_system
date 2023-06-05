@extends('layouts.master')

@section('title','Payment Summary')


@section('content')
    @include('payments.includes.summary')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Complete Payments
        </div>
        <div class="card-body">
            @livewire('payments.payment-filter', ['tablename' => 'complete-payments-table']) <br>
            <livewire:payments.complete-payments-table />
        </div>
    </div>
@endsection