@extends('layouts.master')

@section('title')
    Tax Consultants
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">Duration Configuration</div>
            <div class="card-tools">
                @can('tax-consultant-fee-configuration-add')
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'tax-agent-fee-modal')"><i
                                class="fa fa-plus-circle"></i> Add
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