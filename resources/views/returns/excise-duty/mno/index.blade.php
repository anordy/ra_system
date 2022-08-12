@extends('layouts.master')

@section('title','MNO Excise Duty')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        MNO Excise Duty Returns Summary
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.return-summary',['vars'=>$vars])
    </div>
</div>

<div class="card p-0 m-0">
    <div class="card-header text-uppercase font-weight-bold">
        MNO Excise Duty Returns
    </div>
    <div class="card-body mt-0">
        @livewire('returns.excise-duty.mno-returns-table')
    </div>
</div>
@endsection
