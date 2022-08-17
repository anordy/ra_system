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
        @livewire('reports.returns.previews.bfo-preview-table',['parameters'=>$parameters])
        @break
        @case('hotel-levy')
        @livewire('reports.returns.previews.bfo-preview-table',['parameters'=>$parameters])
        @break

        @case('restaurant-levy')
        this is restaurant levy
        @break

        @case('petroleum-levy')
        this is restaurant levy
        @break

        @case('airport-service-safety-fee')
        this is airport services
        @break
        
        @case('mobile-money-transfer')
        this is airport services
        @break

        @case('electronic-money-transaction')
        this is airport services
        @break

        @case('lumpsum-payment')
        this is airport services
        @break

        @case('sea-service-transport-charge')
        @livewire('reports.returns.previews.sea-port-preview-table',['parameters'=>$parameters])
        @break
        
        @case('stamp-duty')
        @livewire('reports.returns.previews.stamp-duty-preview-table',['parameters'=>$parameters])
        @break
        {{-- @default --}}
        @endswitch
        {{-- @livewire('reports.returns.preview-table',['parameters'=>$parameters]) --}}
    </div>
</div>
@endsection
