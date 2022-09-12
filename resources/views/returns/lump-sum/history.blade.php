@extends('layouts.master')

@section('title', 'Lump Sum Payments History ')

@section('content')

    @livewire('returns.return-summary', ['vars' => $vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

    <div class="card rounded-4 shadow">
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            <div>Payments History</div> <br><br>
            <livewire:returns.return-filter :tablename="$tableName" />
        </div>

        <div class="card-body">
            <livewire:returns.lump-sum.lump-sum-returns-table />
        </div>
    </div>
@endsection
