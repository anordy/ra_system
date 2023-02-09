@extends('layouts.master')

@section('title')
    ISIC 2
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            ISIC LEVEL 2
                <div class="card-tools">
                @can('setting-isic-level-two-add')
                    <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c2-add-modal')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>Add
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c2-import-modal')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>Import
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c2-table')
        </div>
    </div>
@endsection
