@extends('layouts.master')

@section('title')
    Tax Consultants
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Fee Configuration</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'tax-agent-fee-modal')"><i
                            class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('tax-agent.fee-configuration-table')
        </div>
    </div>
@endsection

@section('scripts')

@endsection