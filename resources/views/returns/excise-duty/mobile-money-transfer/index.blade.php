@extends('layouts.master')

@section('title', 'Taxpayers')

@section('content')
    @livewire('returns.return-summary', ['vars' => $vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])
    
    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Mobile Money Transfer Return
        </div>
        <div class="card-body">
            <livewire:returns.excise-duty.mobile-money-transfer-table />
        </div>
    </div>
@endsection
