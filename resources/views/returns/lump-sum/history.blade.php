@extends('layouts.master')

@section('title', 'Stamp Duty Lumpsum Tax Returns History ')

@section('content')

    @livewire('returns.return-summary', ['vars' => $vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Stamp Duty Lumpsum Tax Returns
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            <livewire:returns.return-filter :tablename="$tableName" />
        </div>
        <div class="card-body">
            <livewire:returns.lump-sum.lump-sum-returns-table />
        </div>
    </div>
@endsection
