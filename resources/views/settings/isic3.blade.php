@extends('layouts.master')

@section('title')
    ISIC 3
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            ISIC LEVEL 3
            <div class="card-tools">
            @can('setting-isic-level-three-add')
                <button class="btn btn-primary btn-sm"
                    onclick="Livewire.emit('showModal', 'i-s-i-c3-add-modal')"><i
                        class="bi bi-plus-circle-fill pr-2"></i>
                    Add</button>
                    <button class="btn btn-primary btn-sm"
                    onclick="Livewire.emit('showModal', 'i-s-i-c3-import-modal')"><i
                        class="bi bi-plus-circle-fill pr-2"></i>
                    Import</button>
                @endcan
        </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c3-table')
        </div>
    </div>
@endsection
