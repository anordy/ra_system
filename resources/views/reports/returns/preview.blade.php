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
        @if ($parameters['filing_report_type'] == 'Claims')
            Report preview for Claims of {{ $parameters['tax_type_name'] }} 
            @else
            Report preview of {{ $parameters['tax_type_name'] }}
        @endif
    </div>
    <div class="card-body mt-0">
        @if ($parameters['filing_report_type'] == 'Claims')
            @livewire('reports.returns.previews.tax-claims-preview-table',['parameters'=>$parameters])
        @else
            @switch($parameters['tax_type_code'])
                @case('excise-duty-mno')
                    @livewire('reports.returns.previews.mno-preview-table',['parameters'=>$parameters])
                    @break
                @case('excise-duty-bfo')
                    @livewire('reports.returns.previews.bfo-preview-table',['parameters'=>$parameters])
                    @break
                @case('hotel-levy')
                    @livewire('reports.returns.previews.hotel-levy-preview-table',['parameters'=>$parameters])
                @break

                @case('restaurant-levy')
                    @livewire('reports.returns.previews.restaurant-levy-preview-table',['parameters'=>$parameters])
                    @break

                @case('tour-operator-levy')
                    @livewire('reports.returns.previews.tour-operator-levy-preview-table',['parameters'=>$parameters])
                    @break

                @case('vat')
                    @livewire('reports.returns.previews.vat-preview-table',['parameters'=>$parameters])
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
            @endswitch
        @endif
    </div>
</div>
@endsection
