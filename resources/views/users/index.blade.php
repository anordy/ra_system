@extends('layouts.master')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">User Management</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'user-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('users-table')
        </div>
    </div>
@endsection
