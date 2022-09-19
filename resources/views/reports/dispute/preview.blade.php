@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="d-flex justify-content-start mb-3">
    <a href="{{ route('reports.disputes') }}" class="btn btn-info">
        <i class="fas fa-arrow-left"></i>
        Back
    </a>
</div>
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
       Dispute Report Preview
    </div>
    <div class="card-body mt-0">
    @livewire('reports.dispute.previews.dispute-preview-table',['parameters' => $parameters])
    </div>
</div>
@endsection
