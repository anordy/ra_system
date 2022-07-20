@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <livewire:tax-agent.active-tax-agent-table />
        </div>
    </div>
@endsection