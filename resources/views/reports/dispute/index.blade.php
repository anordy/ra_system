@extends('layouts.master')

@section('title', 'Managerial Reports')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Disputes Reports
        </div>
        <div class="card-body mt-0">
            @livewire('reports.dispute.dispute-report')
        </div>
    </div>
@endsection
