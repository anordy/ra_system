@extends('layouts.master')

@section('title')
    System Settings
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            System Setting Management
            <div class="card-tools">
                @can('system-setting-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-add-modal')"><i
                                class="bi bi-plus-circle-fill pr-1"></i>
                            Add Setting</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.system-settings.system-setting-table')
        </div>
    </div>
@endsection
