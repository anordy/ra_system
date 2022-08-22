@extends('layouts.master')

@section('title','Hotel Returns')

@section('content')
@livewire('returns.return-summary',['vars'=>$vars])
@livewire('returns.return-card-report', ['paidData' => $paidData, 'unpaidData' => $unpaidData])


<div class="card p-0 m-0">
    <div class="card-header text-uppercase font-weight-bold">
        Hotel Returns
    </div>
    <div class="card-body mt-0 p-2">
        <livewire:returns.hotel.hotel-returns-table status='all'></livewire:returns.hotel.hotel-returns-table>
    </div>
</div>
@endsection