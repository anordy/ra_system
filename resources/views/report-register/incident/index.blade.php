@extends('layouts.master')

@section('title','Registered Reports')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Registered Reports</h5>
        </div>
        <div class="card-body">
            @livewire('report-register.incident.incident-table')
        </div>
    </div>
@endsection
