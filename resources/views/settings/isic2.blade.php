@extends('layouts.master')

@section('title')
    ISIC 2
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">ISIC LEVLE 2</h5>
            <div class="card-tools">
                @can('setting-isic-level-two-add')
                    <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c2-add-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Add</button>
                        <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c2-import-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Import</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c2-table')
        </div>
    </div>
@endsection
