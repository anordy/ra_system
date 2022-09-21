@extends('layouts.master')

@section('title', 'Mobile Network Operator Excise Duty')

@section('content')

    <livewire:returns.excise-duty.mno-card-one />
    <livewire:returns.excise-duty.mno-card-two />

    <div class="card mt-3 rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Network Operator Excise Duty Returns
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.excise-duty.mno-returns-table />
        </div>
    </div>
@endsection
