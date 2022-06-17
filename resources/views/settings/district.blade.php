@extends('layouts.master')

@section('title')
    
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Districs Management</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'district-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('district-table')
        </div>
    </div>
@endsection
