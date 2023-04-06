@extends('layouts.master')

@section('title')
    Workflow
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Workflow Configurations
        </div>

        <div class="card-body">
        
            @livewire('workflow.workflow-config-table')
        </div>
    </div>
@endsection
