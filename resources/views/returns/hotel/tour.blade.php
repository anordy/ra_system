@extends('layouts.master')

@section('title', 'Tour Operator Returns History')

@section('content')
@livewire('returns.return-summary', ['vars' => $vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

<div class="card">
    <div class="card-body">
        @livewire('returns.hotel.tour-operator-returns-table')
    </div>
</div>
@endsection