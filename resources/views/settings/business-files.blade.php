@extends('layouts.master')

@section('title')
    Business File Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Business File Types Management
            <div class="card-tools">
                @can('setting-business-file-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                                onclick="Livewire.emit('showModal', 'business.files.add-type-modal')">
                            <i class="bi bi-plus-circle-fill pr-2"></i>
                            New File Type
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('business.files.file-types-table')
        </div>
    </div>
@endsection
