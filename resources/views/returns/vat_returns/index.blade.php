@extends('layouts.master')

@section('title', 'VAT Tax Returns')

@section('content')
<div class="card p-0 m-0 mb-3">

    @livewire('returns.return-summary',['vars'=>$vars])
    @livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])


    <div class="card mt-3">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            VAT Return
        </div>

        <div class="card-body">
            <livewire:returns.vat.vat-return-table />
        </div>
    </div>
    @endsection