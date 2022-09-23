@extends('layouts.master')

@section('title', 'Report preview')

@section('content')
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('reports.debts') }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Report preview for {{ $parameters['report_type'] }}
        </div>
        <div class="card-body mt-0">
            @if ($parameters['report_type'] === 'Returns')
                @livewire('reports.debts.previews.return-debt-report-preview-table', ['parameters' => $parameters])
            @elseif ($parameters['report_type'] === 'Assessments')
                @livewire('reports.debts.previews.assessment-debt-report-preview-table', ['parameters' => $parameters])
            @elseif ($parameters['report_type'] === 'Waiver')
                @livewire('reports.debts.previews.debt-waiver-report-preview-table', ['parameters' => $parameters])
            @elseif ($parameters['report_type'] === 'Demand-Notice')
                @livewire('reports.debts.previews.demand-notice-report-preview-table', ['parameters' => $parameters])
            @endif
        </div>
    </div>
@endsection
