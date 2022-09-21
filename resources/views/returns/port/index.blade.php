@extends('layouts.master')

@section('title', 'Port Return')

@section('content')
    <livewire:returns.port.port-card-one />
    <livewire:returns.port.port-card-two />

    <div class="card mt-3 rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Port Tax Return
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.port.port-return-table />
        </div>
    </div>
@endsection
