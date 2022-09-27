@extends('layouts.master')

@section('title', 'Tax Consultants')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h6 class="text-capitalize">Details for {{$agent->taxpayer->first_name.' '.$agent->taxpayer->middle_name.' '.$agent->taxpayer->last_name}} as Tax Consultant</h6>
                <a class="btn btn-info" href="{{ route('taxagents.active') }}">
                    <i class="bi bi-arrow-return-left mr-2"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-end p-2">
                @if ($agent->status == \App\Models\TaxAgentStatus::APPROVED && $agent->bill->status == \App\Models\PaymentStatus::PAID)
                    <div class="d-flex justify-content-end">
                        <a style="background: #f5f9fa; color: #3c5f86;" class="file-item" target="_blank"
                           href="{{ route('taxagents.certificate', [\Illuminate\Support\Facades\Crypt::encrypt($agent->id)]) }}">
                            <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                            <div style="font-weight: 500;" class="ml-1">
                                Download Certificate
                            </div>
                        </a>
                    </div>
                @endif
            </div>
            @include('taxagents.includes.show')

        </div>
    </div>

@endsection