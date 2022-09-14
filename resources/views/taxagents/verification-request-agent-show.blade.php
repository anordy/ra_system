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
            @if(empty($fee))
                <div class=" alert alert-danger d-flex justify-content-between" >
                    <div class="d-flex justify-content-start  align-items-center">
                        <div>
                            <i style="font-size: 30px;" class="bi bi-x-circle mr-1"></i>
                        </div>
                        <div>
                            Please kindly add registration fee before approving any request
                        </div>
                    </div>

                    <div >
                        <a class="btn btn-primary" href="{{ route('taxagents.fee') }}">
                            <i class="bi bi-plus-square-fill mr-2"></i>
                            Add Fee
                        </a>
                    </div>
                </div>
            @else
                @if($agent->status == \App\Models\TaxAgentStatus::PENDING)
                <div class="d-flex justify-content-end p-2">
                    <livewire:tax-agent.verify-action :taxagent=$agent></livewire:tax-agent.verify-action>
                </div>
                @endif
            @endif
                @include('taxagents.includes.show')

        </div>

    </div>

@endsection
