@extends('layouts.master')

@section('title', 'Stamp Duty Composition Tax Return')

@section('content')
    <livewire:returns.stamp-duty.stamp-duty-card-one />
    <livewire:returns.stamp-duty.stamp-duty-card-two />

    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Stamp Duty Composition Tax Return
        </div>
        <div class="card rounded-4 shadow-none">

            <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1 pb-4">
                @livewire('returns.return-filter', ['tablename' => $tableName, 'cardOne' => $cardOne, 'cardTwo' => $cardTwo])
            </div>

            <div class="card-body pt-4">
                <livewire:returns.stamp-duty.stamp-duty-returns-table />
            </div>
        </div>
    </div>
@endsection
