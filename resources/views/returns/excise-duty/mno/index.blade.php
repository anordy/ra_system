@extends('layouts.master')

@section('title','MNO Excise Duty')

@section('content')

    @livewire('returns.return-summary',['vars'=>$vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])


<div class="card p-0 m-0 mt-2">
    <div class="card-header text-uppercase font-weight-bold">
        MNO Excise Duty Returns
    </div>
    <div class="card-body mt-0">
        @livewire('returns.excise-duty.mno-returns-table')
    </div>
</div>
@endsection
