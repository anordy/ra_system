@extends('layouts.master')

@section('title', 'Failed Payments')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Failed Payments
        </div>
        <div class="card-body">
            <livewire:payments.failed-payments-table />
        </div>
    </div>
@endsection
