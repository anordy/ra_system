@extends('layouts.master')

@section('title')
    Withholding Agents
@endsection

@section('content')

        <div class="card-body">
            @livewire('withholding-agents.withholding-agent-registration')
        </div>

@endsection
