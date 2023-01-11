@extends('layouts.master')

@section('title')
    Users
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">API Users Management</h5>
            <div class="card-tools">
                @can('setting-user-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.api-users.add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.api-users.api-users-table')
        </div>
    </div>
@endsection
