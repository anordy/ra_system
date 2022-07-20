@extends('layouts.master')

@section('title')
    Workflow
@endsection

@section('content')
    <div class="card">
        <div class="card-header p-2 m-0">
            <h6 class="text-uppercase">Workflow {{ $workflow->name }} Configurations</h6>
        </div>

        <div class="card-body">
            @livewire('workflow.workflow-config', ['id' => encrypt($workflow->id)])
        </div>
    </div>
@endsection
