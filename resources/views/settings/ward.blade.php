@extends('layouts.master')

@section('title')
    Wards
@endsection

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Wards
            <div class="card-tools">
                @can('setting-ward-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'ward-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-1"></i> Add New Ward
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
