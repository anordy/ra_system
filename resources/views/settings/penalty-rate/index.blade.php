@extends('layouts.master')

@section('title')
    Penalty Rate
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Penalty Rate Management
            <div class="card-tools">
                @can('setting-exchange-rate-add')
                    <button class="btn btn-primary btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.penalty-rate.penalty-rate-add-modal')">
                        <i class="bi bi-plus-circle-fill pr-1"></i> Add New Rate
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.penalty-rate.penalty-rates-table')
        </div>
    </div>
@endsection
