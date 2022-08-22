@extends('layouts.master')

@section('title', 'Tax Consultants ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Registration Details</h6>
            <div class="card-tools">
                <a class="btn btn-info" href="{{ route('taxagents.requests') }}">
                    <i class="bi bi-arrow-return-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">
            @include('taxagents.includes.show')
        </div>
        <div class="d-flex justify-content-end p-2">
            @if ($agent->status == 'verified' && $agent->bill->status == \App\Models\PaymentStatus::PAID)
                <livewire:tax-agent.actions :taxagent=$agent></livewire:tax-agent.actions>
            @endif
        </div>
    </div>

@endsection
