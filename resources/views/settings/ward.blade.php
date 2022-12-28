@extends('layouts.master')

@section('title')
    Wards
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Wards</h5>
            <div class="card-tools">
                @can('setting-ward-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'ward-add-modal')">
                            <i class="fa fa-plus-circle"></i> Add
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('ward-table')
        </div>
    </div>
@endsection
