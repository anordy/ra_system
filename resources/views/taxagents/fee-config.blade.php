@extends('layouts.master')

@section('title')
    Tax Consultants
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Duration Configuration
            <div class="card-tools">
                @can('tax-consultant-fee-configuration-add')
                    <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'tax-agent-fee-modal')"><i
                                class="bi bi-plus-circle-fill pr-1"></i> Add Duration
                    </button>
                @endcan

            </div>
        </div>

        <div class="card-body">
            @livewire('tax-agent.fee-configuration-table')
        </div>
    </div>
@endsection

@section('scripts')

@endsection