@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Registered Condominium Properties</h5>
        </div>
        <div class="card-body">
            @livewire('property-tax.condominium.registered-condominiums-table')
        </div>
    </div>
@endsection