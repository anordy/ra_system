@extends('layouts.master')

@section('title')
    VAT Tax Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            VAT Tax Types
        </div>

        <div class="card-body">
            @livewire('sub-vat-tax-types-table')
        </div>
    </div>
@endsection
