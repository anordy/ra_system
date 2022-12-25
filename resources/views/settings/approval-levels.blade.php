@extends('layouts.master')

@section('title')
    Approval Levels
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Approval Levels Management</h5>
        </div>

        <div class="card-body">
            @livewire('settings.approval-level.approval-level-table')
        </div>
    </div>
@endsection
