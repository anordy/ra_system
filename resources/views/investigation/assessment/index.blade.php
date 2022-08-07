@extends('layouts.master')

@section('title', 'Investigation with Assessment')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Investigation with Assessements
        </div>
        <div class="card-body">
            @livewire('investigation.tax-investigation-assessment-table')
        </div>
    </div>
@endsection
