@extends('layouts.master')

@section('title','Registered Condominium Properties')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Property Payment Extension Requests</h5>
            <div class="card-tools">
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'property-tax.payment-extension.extend-due-date-modal')">
                            <i class="fa fa-plus-circle"></i>Extend Due Date
                        </button>
            </div>
        </div>
        <div class="card-body">
            @livewire('property-tax.payment-extension.payment-extension-table')
        </div>
    </div>
@endsection