@extends('layouts.master')

@section('title', 'TaxAgents')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:tax-agent.tax-agent-table />
        </div>
    </div>
@endsection