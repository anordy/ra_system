@extends('layouts.master')

@section('title', 'BFO Excise Duty Return')

@section('content')

@livewire('returns.return-summary',['vars'=>$vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

<div class="card mt-3">
    <div class="card-header text-uppercase font-weight-bold bg-white">
        BFO Excise Duty Return
    </div>

    <div class="card-body">
        <livewire:returns.bfo-excise-duty.bfo-excise-duty-table />
    </div>
</div>

@endsection