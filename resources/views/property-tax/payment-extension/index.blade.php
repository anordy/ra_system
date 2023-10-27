@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Property Payment Extension Requests</h5>
        </div>
        <div class="card-body">
            @livewire('property-tax.payment-extension.payment-extension-table')
        </div>
    </div>
@endsection