@extends('layouts.master')

@section('title')
Roles
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="text-uppercase">Roles Management</h5>
        <div class="card-tools">
            @can('setting-role-add')
            <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'role-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        @livewire('roles-table')
    </div>
</div>
@endsection