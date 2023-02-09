@extends('layouts.master')

@section('title')
    Interest Rates
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Interest Rates
            <div class="card-tools">
                @can('setting-interest-rate-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                                onclick="Livewire.emit('showModal', 'settings.interest-rate.interest-rate-add-modal')">
                            <i class="bi bi-plus-circle-fill"></i> Add New Rate
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.interest-rate.interest-rates-table')
        </div>
    </div>
@endsection
