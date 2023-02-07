@extends('layouts.master')

@section('title')
    Tax Regions Management
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold bg-white text-uppercase">
            Tax Regions Management
            <div class="card-tools">
                @can('setting-tax-region-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3"
                                onclick="Livewire.emit('showModal', 'settings.tax-region.tax-region-add-modal')">
                            <i class="fa fa-plus-circle mr-1"></i>
                            Add tax region
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.tax-region.tax-regions-table')
        </div>
    </div>
@endsection
