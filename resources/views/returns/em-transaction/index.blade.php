@extends('layouts.master')

@section('title','Electronic Money Transaction')

@section('content')
<div class="card p-0 m-0 mb-3">
    <div class="card-header text-uppercase">
        Summary
    </div>
    <div class="card-body mt-0 p-2">
        @livewire('returns.return-summary',['vars'=>$vars])
    </div>
</div>
    <div class="card">
        <div class="card-body">
            <livewire:returns.em-transaction.em-transactions-table />
        </div>
    </div>
@endsection