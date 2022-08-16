@extends('layouts.master')

@section('title', 'Tour Operator Returns History')

@section('content')
    <div class="card p-0 m-0 mb-3">
        <div class="card-header text-uppercase">
            Summary
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('returns.return-summary', ['vars' => $vars])
        </div>
    </div>

    <div class="card">
        @livewire('returns.return-card-report', ['data' => $data])

        <div class="card-body">
            @livewire('returns.hotel.tour-operator-returns-table')
        </div>
    </div>
@endsection
