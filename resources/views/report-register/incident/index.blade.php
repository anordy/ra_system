@extends('layouts.master')

@section('title','Taxpayer Incidents')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Taxpayer Incidents</h5>
        </div>
        <div class="card-body">
            @livewire('report-register.incident.incident-table')
        </div>
    </div>
@endsection
