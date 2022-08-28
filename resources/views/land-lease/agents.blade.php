@extends('layouts.master')

@section('title', 'Land Lease Agents')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Land Lease Agents</h5>
            <div class="card-tools">
                @can('land-lease-register-agent')
                    <a href="{{route('land-lease.agent.create')}}">
                        <button class="btn btn-info btn-sm"><i class="fa fa-plus-circle"></i>Register</button>
                    </a>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <livewire:land-lease.agents-table/>
        </div>
    </div>
@endsection

