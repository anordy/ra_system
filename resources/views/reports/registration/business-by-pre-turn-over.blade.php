@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Business Registration urn Over Reports
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registration.previews.business.business-turn-over-last-preview-table',['from'=>$from,'to'=>$to])
    </div>
</div>
@endsection
