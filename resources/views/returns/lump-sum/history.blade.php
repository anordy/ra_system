@extends('layouts.master')

@section('title', 'Stamp Duty Lumpsum Tax Returns History')

@section('content')
    <livewire:returns.lump-sum.lump-sum-card-one />
    <livewire:returns.lump-sum.lump-sum-card-two />

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Stamp Duty Lumpsum Tax Returns
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>
        <div class="card-body">
            <livewire:returns.lump-sum.lump-sum-returns-table />
        </div>
    </div>
@endsection
