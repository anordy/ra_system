@extends('layouts.master')

@section('title', 'BFO Excise Duty Return')

@section('content')

    <div class="card p-0 m-0 mb-3">
        <div class="card-header text-uppercase">
            Summary
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('returns.return-summary',['vars'=>$vars])
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            BFO Excise Duty Return
        </div>
        <div class="card-body">
            <livewire:returns.bfo-excise-duty.bfo-excise-duty-table />
        </div>
    </div>
    
@endsection