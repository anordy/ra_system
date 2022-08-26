@extends('layouts.master')

@section('title','Port Return')

@section('content')
@livewire('returns.return-summary',['vars'=>$vars])
{{-- @include('returns.port.includes.payment-cards') --}}

@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])


<div class="card">
     <div class="card-header text-uppercase font-weight-bold bg-white">
        Port Return
    </div>
    <div class="card-body">
        @livewire('returns.port.port-return-table')
    </div>
</div>
@endsection