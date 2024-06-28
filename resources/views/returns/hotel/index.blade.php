@extends('layouts.master')

@section('title', 'Hotel Tax Returns')

@section('content')
    <livewire:returns.hotel.hotel-card-one />
    <livewire:returns.hotel.hotel-card-two />

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Hotel Returns
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
        </div>

        <div class="card-body">
            <livewire:returns.hotel.hotel-returns-table />
        </div>
    </div>
@endsection
