@extends('layouts.master')
@section('title', 'Users List')
@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            User Management
            <div class="card-tools">
                @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-primary btn-sm px-3" onclick="Livewire.emit('showModal', 'user-add-modal')">
                        <i class="bi bi-plus-circle-fill pr-2"></i>Add new user
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @livewire('users-table')
        </div>
    </div>
@endsection
