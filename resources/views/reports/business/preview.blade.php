@extends('layouts.master')

@section('title','Business Report preview')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Preview of Business Registrations
    </div>
    <div class="card-body mt-0">
        @livewire('reports.business.preview-table',['parameters'=>$parameters])
    </div>
</div>
@endsection
