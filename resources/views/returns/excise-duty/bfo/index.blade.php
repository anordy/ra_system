@extends('layouts.master')

@section('title', 'BFO Excise Duty Return')

@section('content')
    <livewire:returns.bfo-excise-duty.bfo-card-one />
    <livewire:returns.bfo-excise-duty.bfo-card-two />

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            BFO Excise Duty Return
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.bfo-excise-duty.bfo-excise-duty-table />
        </div>
    </div>

@endsection
