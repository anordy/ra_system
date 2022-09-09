@extends('layouts.master')

@section('title', 'Managerial Reports')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Return Reports
        </div>
        <div class="card-body mt-0">
            @livewire('reports.returns.return-report')
        </div>
    </div>
@endsection
