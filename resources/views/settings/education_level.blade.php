@extends('layouts.master')

@section('title')
    Education Level
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Education Level
            <div class="card-tools">
                @can('setting-education-level-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                                onclick="Livewire.emit('showModal', 'education-level-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-1"></i> Add New Level
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('education-level-table')
        </div>
    </div>
@endsection
