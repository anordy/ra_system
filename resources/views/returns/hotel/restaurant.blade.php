@extends('layouts.master')

@section('title', 'Restaurant Returns History')

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
        @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

        <div class="card-body">
            @livewire('returns.hotel.restaurant-returns-table')
        </div>
    </div>
@endsection
