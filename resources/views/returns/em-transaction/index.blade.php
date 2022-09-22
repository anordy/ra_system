@extends('layouts.master')

@section('title', 'Electronic Money Transaction Tax Returns')

@section('content')

    <livewire:returns.em-transaction.em-card-one />
    <livewire:returns.em-transaction.em-card-two />

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Electronic Money Transactions Tax Returns
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.em-transaction.em-transactions-table />
        </div>
    </div>
@endsection
