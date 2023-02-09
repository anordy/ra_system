@extends('layouts.master')

@section('title')
    System Setting Category
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            System Setting Category Management
            <div class="card-tools">
                @can('setting-system-category-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-category-add-modal')"><i
                                class="bi bi-plus-circle-fill pr-1"></i>
                            Add Category</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.system-settings.system-setting-category-table')
        </div>
    </div>
@endsection
