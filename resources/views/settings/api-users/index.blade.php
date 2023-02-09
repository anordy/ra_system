@extends('layouts.master')

@section('title')
    Users
@endsection

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            API Users Management
            <div class="card-tools">
                @can('setting-user-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-primary btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.api-users.add-modal')"><i
                        class="bi bi-plus-circle-fill pr-1"></i>
                    Add API User</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.api-users.api-users-table')
        </div>
    </div>
@endsection
