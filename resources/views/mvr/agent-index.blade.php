@extends('layouts.master')

@section('title', 'Transport Agents')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            Transport Agents
            <div class="card-tools">
                @can('mvr_register_agent')
                    <a href="{{route('mvr.agent.create')}}">
                        <button class="btn btn-info btn-sm"><i class="bi bi-plus-circle-fill"></i>Register</button>
                    </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <livewire:mvr.agents-table />
        </div>
    </div>
@endsection

