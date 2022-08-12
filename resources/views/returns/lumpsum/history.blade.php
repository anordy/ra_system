@extends('layouts.master')

@section('title', 'Lump Sum Payments History ')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        Summary
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.return-summary',['vars'=>$vars])
    </div>
</div>

<div class="card rounded-0">
    <div class="card-header bg-white h-100 d-flex justify-content-between align-items-center rounded-1">
        <div>Payments History</div>
    </div>

        @livewire('returns.return-card-report', ['data' => $data])
        <div class="card-body">
            <livewire:returns.lump-sum.lump-sum-returns-table />

    </div>
</div>
@endsection