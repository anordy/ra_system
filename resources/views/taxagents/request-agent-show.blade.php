@extends('layouts.master')

@section('title', 'Tax Consultants ')

@section('content')
    <div class="card">
        <div class="card-header">
            <h6>Registration Details</h6>
            <div class="card-tools">
                <a class="btn btn-outline-info" href="{{ route('taxagents.requests') }}">Back</a>
            </div>
        </div>

        @include('taxagents.includes.show')

        <div class="d-flex justify-content-end p-2">
            @if ($agent->status == 'verified' && $agent->bill->payment->status == \App\Models\PaymentStatus::PAID)
                <livewire:tax-agent.actions :taxagent=$agent></livewire:tax-agent.actions>
            @endif
        </div>
    </div>

@endsection
