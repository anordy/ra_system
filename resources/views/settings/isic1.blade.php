@extends('layouts.master')

@section('title')
    ISIC 1
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">ISIC LEVLE 1</h5>
            <div class="card-tools">
                @can('setting-isic-level-one-add')
                    <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c1-add-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Add</button>
                        <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c1-import-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Import</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c1-table')
        </div>
    </div>
@endsection
