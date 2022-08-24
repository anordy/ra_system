@extends('layouts.master')

@section('title','Managerial Reports')

@section('content')
<div class="card">
    <div class="card-header text-uppercase font-weight-bold">
        Registration Reports
    </div>
    <div class="card-body mt-0">
        @livewire('reports.registrations.registration-report')
    </div>
</div>
@endsection
