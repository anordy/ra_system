@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('reports.returns') }}" class="btn btn-info">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Report preview of {{ $parameters['tax_type_name'] }}.
    </div>
    <div class="card-body mt-0">
        @switch($parameters['tax_type_code'])
        @case('excise-duty-mno')
        @livewire('reports.returns.previews.mno-preview-table',['parameters'=>$parameters])
        @break
        @case('excise-duty-bfo')
        this is bfo
        @break
        @case('hotel-levy')
        this is hotel levy
        @break

        @case('restaurant-levy')
        this is restaurant levy
        @break

        @case('petroleum-levy')
        @livewire('reports.returns.previews.petroleum-levy-preview-table',['parameters'=>$parameters])
        @break

        @case('airport-service-safety-fee')
        @livewire('reports.returns.previews.air-port-preview-table',['parameters'=>$parameters])
        @break
        
        @case('mobile-money-transfer')
        @livewire('reports.returns.previews.mm-transfer-preview-table',['parameters'=>$parameters])
        @break

        @case('electronic-money-transaction')
        @livewire('reports.returns.previews.em-transaction-preview-table',['parameters'=>$parameters])
        @break

        @case('lumpsum-payment')
        @livewire('reports.returns.previews.lump-sum-preview-table',['parameters'=>$parameters])
        @break

        @case('sea-service-transport-charge')
        @livewire('reports.returns.previews.sea-port-preview-table',['parameters'=>$parameters])
        @break
        
        @case('stamp-duty')
        @livewire('reports.returns.previews.stamp-duty-preview-table',['parameters'=>$parameters])
        @break
        {{-- @default --}}
        @endswitch
    </div>
</div>
@endsection
