@extends('layouts.master')

@section('title','Return Report preview')

@section('content')
<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('reports.returns') }}" class="btn btn-info">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        {{ $parameters['type']=='Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'] }} Report preview for {{ $parameters['tax_type_name'] }} Returns 
        @if ($parameters['dates']['startDate'])
            From <span class="text-primary">{{date("M, d Y", strtotime($parameters['dates']['from'])) }}</span> To <span class="text-primary">{{ date("M, d Y", strtotime($parameters['dates']['to']))}}</span>
        @endif
    </div>
    <div class="card-body mt-0">
        @livewire('reports.returns.previews.report-preview-table',['parameters'=>$parameters])
    </div>
</div>
@endsection
