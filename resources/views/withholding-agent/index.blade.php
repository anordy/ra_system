@extends('layouts.master')

@section('title')
    Withholding Agents
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Withholding Agents</h5>
        </div>

        <div class="card-body">
            @livewire('withholding-agents.withholding-agents-table')
        </div>
    </div>
@endsection
