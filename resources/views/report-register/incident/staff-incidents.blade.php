@extends('layouts.master')

@section('title','Staff Incidents')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Staff Incidents</h5>
            <div class="card-tools">
                <button class="btn btn-primary btn-sm px-3"
                        onclick="Livewire.emit('showModal', 'report-register.incident.add-incident')">
                    <i class="bi bi-plus-circle-fill pr-2"></i>
                    New Incident
                </button>
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.incident.staff-incident-table')
        </div>
    </div>
@endsection
