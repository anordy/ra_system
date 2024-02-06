@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Register Condominium Property</h5>
        </div>
        <div class="card-body">
             @livewire('property-tax.condominium.condominium-registration')
        </div>
    </div>
@endsection