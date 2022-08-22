@extends('layouts.master')

@section('title', 'Restaurant Returns History')

@section('content')

@livewire('returns.return-summary', ['vars' => $vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])


<div class="card">
    <div class="card-body">
        @livewire('returns.hotel.restaurant-returns-table')
    </div>
</div>
@endsection