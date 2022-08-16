@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Report preview
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
        this is airport services
        @break
        
        @case('stamp-duty')
        this is airport services
        @break
        {{-- @default --}}
        @endswitch
        {{-- @livewire('reports.returns.preview-table',['parameters'=>$parameters]) --}}
    </div>
</div>
@endsection
