@extends('layouts.master')

@section('title','Managerial Report Businesses Preview')

@section('content')
<div class="card">
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-info">
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>
    <div class="card-header text-uppercase font-weight-bold">
        Managerial Report Businesses Preview
    </div>
    <div class="card-body mt-0">
        @livewire('reports.business.preview-table',['parameters'=>$parameters])
    </div>
</div>
@endsection
