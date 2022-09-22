@extends('layouts.master')

@section('title', 'VAT Tax Returns')

@section('content')
    <div>
        <livewire:returns.vat.vat-card-one />
        <livewire:returns.vat.vat-card-two />

        <div class="card mt-3 ">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                VAT Return
            </div>
            <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
                @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
            </div>

            <div class="card-body">
                <livewire:returns.vat.vat-return-table />
            </div>
        </div>
    </div>
@endsection
