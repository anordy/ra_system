@extends('layouts.master')

@section('title')
    Business File Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="text-uppercase">Business File Types Management</h6>
            <div class="card-tools">
                @can('setting-business-file-add')
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'business.files.add-type-modal')">
                    <i class="bi bi-plus-circle-fill"></i>
                    New File Type</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('business.files.file-types-table')
        </div>
    </div>
@endsection
