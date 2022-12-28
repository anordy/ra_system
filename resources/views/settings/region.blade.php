@extends('layouts.master')

@section('title')
    Region
@endsection

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Region Management
            <div class="card-tools">
                @can('setting-region-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'region-add-modal')"><i
                                class="bi bi-plus-circle-fill mr-1"></i>
                            Add New Region
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body">
        @livewire('region-table')
    </div>
    </div>
@endsection
