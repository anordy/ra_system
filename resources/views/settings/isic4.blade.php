@extends('layouts.master')

@section('title')
    ISIC 4
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">ISIC LEVLE 4</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'i-s-i-c4-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'i-s-i-c4-import-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Import</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('i-s-i-c4-table')
        </div>
    </div>
@endsection
