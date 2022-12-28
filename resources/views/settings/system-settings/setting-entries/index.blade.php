@extends('layouts.master')

@section('title')
    System Settings
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">System Setting Category Management</h5>
            <div class="card-tools">
                @can('system-setting-add')
                @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.system-settings.system-setting-table')
        </div>
    </div>
@endsection
