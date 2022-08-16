@extends('layouts.master')

@section('title', 'Returns Debt Management')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            {{ str_replace('-', ' ', $taxType) }} Debts Management
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.port-return-table taxType="{{ $taxType }}" />
        </div>
    </div>
@endsection
