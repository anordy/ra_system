@extends('layouts.master')

@section('title', 'TaxAgents')

@section('content')

    @if($fee == null)
    <div class=" alert alert-danger">
        <div class="d-flex justify-content-start  align-items-center">
            <div>
                <i style="font-size: 30px;" class="bi bi-x-circle mr-1"></i>
            </div>
            <div>
                Please kindly add configuration fee before approving any request
            </div>
        </div>
    </div>
    @endif
    <div class="card mt-3">
        <div class="card-body">
            <livewire:tax-agent.tax-agent-table />
        </div>
    </div>
@endsection