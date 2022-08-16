@extends('layouts.master')

@section('title','Report preview')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Report preview
    </div>
    <div class="card-body mt-0">
        @livewire('reports.returns.preview-table',['parameters'=>$parameters])
    </div>
</div>
@endsection
