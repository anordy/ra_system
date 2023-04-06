@extends('layouts.master')

@section('title')
    Roles
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Roles Management
            <div class="card-tools">
                @can('setting-role-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'role-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-2"></i>
                            Add new role
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('roles-table')
        </div>
    </div>
@endsection