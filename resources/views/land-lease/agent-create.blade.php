@extends('layouts.master')

@section('title', 'Land Lease Agent Registration')

@section('content')
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="d-flex justify-content-start mb-3 mt-3">
                <a href="{{ route('land-lease.agents') }}" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
            <div class="card m-3">
                <div class="card-body">
                    <h3 class="m-3">Agent Details</h3>
                    @livewire('land-lease.agent-registration')
                </div>
            </div>
        </div>
    </div>
@endsection

