@extends('layouts.master')

@section('title')
    System Setting Category
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">System Setting Category Management</h5>
            <div class="card-tools">
                @can('setting-system-category-add')
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.system-settings.system-setting-category-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.system-settings.system-setting-category-table')
        </div>
    </div>
@endsection
