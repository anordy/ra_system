@extends('layouts.master')

@section('title', 'Streets')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Streets
            <div class="card-tools">
                @can('setting-street-add')
                    @if (approvalLevel(auth()->user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'street-add-modal')">
                            <i class="fa fa-plus-circle"></i> Add
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
