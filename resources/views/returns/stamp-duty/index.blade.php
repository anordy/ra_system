@extends('layouts.master')

@section('title', 'Stamp Duty Return')

@section('content')
@livewire('returns.return-summary',['vars'=>$vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])

<div class="card mt-3">
    <div class="card-header text-uppercase font-weight-bold bg-white">
        Stamp Duty Return
    </div>
    <div class="card-body">
        <livewire:returns.stamp-duty.stamp-duty-returns-table />
    </div>
</div>
@endsection