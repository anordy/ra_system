@extends('layouts.master')

@section('title')
    Non Generated Bills
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Non Generated Bills
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('property-tax.payments.non-bills-table')
        </div>
@endsection