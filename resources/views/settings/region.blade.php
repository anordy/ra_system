@extends('layouts.master')

@section('title')
Region
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="text-uppercase">Region Management</h5>
        <div class="card-tools">
            @can('setting-region-add')
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'region-add-modal')"><i
                        class="fa fa-plus-circle"></i>Add
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        @livewire('region-table')
    </div>
</div>
@endsection