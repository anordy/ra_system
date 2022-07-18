@extends('layouts.master')

@section('title')
    Workflow
@endsection

@section('content')
    <div class="card">
        <div class="card-header p-2 m-0">
            <h6 class="text-uppercase">Workflow Configurations</h6>
        </div>

        <div class="card-body">
        
            @livewire('workflow.workflow-config-table')
        </div>
    </div>
@endsection
