@extends('layouts.master')
@extends('layouts.master')

@section('title', 'Airport Return')

@section('content')
    @livewire('returns.return-summary', ['vars' => $vars])

    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

    <div class="card mt-3 rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Airport Tax Return
        </div>
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            <livewire:returns.return-filter :tablename="$tableName" />
        </div>

        <div class="card-body">
            <livewire:returns.port.airport-return-table />
        </div>
    </div>
@endsection
