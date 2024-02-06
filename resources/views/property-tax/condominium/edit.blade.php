@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Edit Condominium Property</h5>
        </div>
        <div class="card-body">
             @livewire('property-tax.condominium.condominium-edit', ['id' => $id])
        </div>
    </div>
@endsection