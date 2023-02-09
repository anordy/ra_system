@extends('layouts.master')

@section('title', 'Streets')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Streets
            <div class="card-tools">
                @can('setting-street-add')
                    @if (approvalLevel(auth()->user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'street-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-1"></i> Add New Street
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('street-table')
        </div>
    </div>
@endsection
