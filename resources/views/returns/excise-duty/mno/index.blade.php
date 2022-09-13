@extends('layouts.master')

@section('title', 'Moblie Network Operator Excise Duty')

@section('content')

    @livewire('returns.return-summary', ['vars' => $vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

    <div class="card mt-3 ">
        <div class="card-header bg-white h-100 justify-content-between align-items-center rounded-1">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                Moblie Network Operator Excise Duty Returns
            </div> <br><br>
            <livewire:returns.return-filter :tablename="$tableName" />
        </div>

        <div class="card-body">
            <livewire:returns.excise-duty.mno-returns-table />
        </div>
    </div>
@endsection
