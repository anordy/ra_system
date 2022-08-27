@extends('layouts.master')

@section('title','Port Return')

@section('content')
@livewire('returns.return-summary',['vars'=>$vars])
@include('returns.port.includes.payment-cards')

<div class="card">
    <div class="card-body">
        @livewire('returns.port.port-return-table')
    </div>
</div>
@endsection