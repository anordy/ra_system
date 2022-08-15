@extends('layouts.master')

@section('title', 'Lump Sum Payments History ')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white h-100 d-flex justify-content-between align-items-center rounded-1">
            <div>Payments History</div>
        </div>

        <div class="card-body">
            <livewire:returns.lump-sum.lump-sum-returns-table />

        </div>
    </div>
@endsection
