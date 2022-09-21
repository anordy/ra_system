@extends('layouts.master')

@section('title', 'Mobile Money Transfer Tax Returns')

@section('content')
    <livewire:returns.excise-duty.mobile-money-card-one />
    <livewire:returns.excise-duty.mobile-money-card-two />

    <div class="card rounded-0 mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Money Transfer Tax Return
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.excise-duty.mobile-money-transfer-table />
        </div>
    </div>
@endsection
