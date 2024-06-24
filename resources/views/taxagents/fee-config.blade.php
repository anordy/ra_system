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
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'tax-agent-fee-modal')"><i
                                    class="bi bi-plus-circle-fill"></i> Add
                        </button>
                    @endif
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