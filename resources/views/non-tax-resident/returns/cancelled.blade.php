@extends('layouts.master')

@section('title', 'Filed Tax Returns')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Cancelled Tax Returns
        </div>
        <div class="card-body">
            <livewire:non-tax-resident.returns.returns-table status="{{ \App\Enum\NonTaxResident\NtrReturnStatus::CANCELLED }}" />
        </div>
    </div>
@endsection
