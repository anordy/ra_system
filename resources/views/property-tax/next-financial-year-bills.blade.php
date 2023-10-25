@extends('layouts.master')

@section('title')
    Next Financial Year Bills
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Next Financial Year Bills
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('property-tax.next-property-tax-payment-table')
        </div>
@endsection

