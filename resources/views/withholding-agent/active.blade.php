@extends('layouts.master')

@section('title')
    Withholding Agents
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">Withholding Agents</div>
        </div>

        <div class="card-body">
            @livewire('withholding-agents.withholding-agents-table')
        </div>
    </div>
@endsection
