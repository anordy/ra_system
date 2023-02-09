@extends('layouts.master')

@section('title')
ISIC 4
@endsection

@section('content')
<div class="card">
    <div class="card-header font-weight-bold text-uppercase bg-white">
        ISIC LEVEL 4
        <div class="card-tools">
        @can('setting-isic-level-four-add')
            <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c4-add-modal')">
                <i class="fa fa-plus-circle"></i> Add
            </button>
            <button class="btn btn-primary btn-sm" onclick="Livewire.emit('showModal', 'i-s-i-c4-import-modal')"><i
                    class="fa fa-plus-circle"></i>
                Import
            </button>
        @endcan
    </div>
    </div>

    <div class="card-body">
        @livewire('i-s-i-c4-table')
    </div>
</div>
@endsection