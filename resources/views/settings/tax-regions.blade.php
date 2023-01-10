@extends('layouts.master')

@section('title')
    Tax Regions Management
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Tax Regions Management</h5>
            <div class="card-tools">
                @can('setting-tax-region-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.tax-region.tax-region-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.tax-region.tax-regions-table')
        </div>
    </div>
@endsection
