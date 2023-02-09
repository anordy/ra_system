@extends('layouts.master')

@section('title')
    Districts
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Districts Management
            <div class="card-tools">
                @can('setting-district-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'district-add-modal')"><i
                                class="bi bi-plus-circle-fill mr-1"></i>
                            Add New District
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('district-table')
        </div>
    </div>
@endsection
