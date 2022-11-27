@extends('layouts.master')

@section('title', 'Cancelled Payments')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Cancelled Payments
        </div>
        <div class="card-body">
            <livewire:payments.cancelled-payments-table />
        </div>
    </div>
@endsection
