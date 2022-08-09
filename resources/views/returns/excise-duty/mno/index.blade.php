@extends('layouts.master')

@section('title','MNO Excise Duty')

@section('content')
<div class="card p-0 m-0">
    <div class="card-header text-uppercase font-weight-bold">
        MNO Excise Duty Returns
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.excise-duty.mno-returns-table')
    </div>
</div>
@endsection
