@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('reports.claims.init') }}" class="btn btn-info">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Report preview
    </div>
    <div class="card-body mt-0">
        @livewire('reports.claims.previews.report-preview-table',['parameters'=>$parameters])
    </div>
</div>
@endsection
