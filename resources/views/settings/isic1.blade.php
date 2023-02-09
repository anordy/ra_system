@extends('layouts.master')

@section('title')
    ISIC 1
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            ISIC LEVEL 1
            <div class="card-tools">
                @can('setting-isic-level-one-add')
                    <button class="btn btn-primary btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c1-add-modal')">
                        <i class="bi bi-plus-circle-fill pr-1"></i>Add New Level
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c1-import-modal')">
                        <i class="bi bi-plus-circle-fill pr-1"></i> Import
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c1-table')
        </div>
    </div>
@endsection
