@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    <div class="card p-0 m-0 mb-3">
        <div class="card-header text-uppercase">
            Summary
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('returns.return-summary', ['vars' => $vars])
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Money Transfer Return
        </div>
        @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])
        <div class="card-body">
            <livewire:returns.excise-duty.mobile-money-transfer-table />
        </div>
    </div>
@endsection
