@extends('layouts.master')

@section('title')
    ISIC 3
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">ISIC LEVLE 3</h5>
            <div class="card-tools">
                @can('setting-isic-level-three-add')
                    <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c3-add-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Add</button>
                        <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'i-s-i-c3-import-modal')"><i
                            class="fa fa-plus-circle"></i>
                        Import</button>
                    @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c3-table')
        </div>
    </div>
@endsection
