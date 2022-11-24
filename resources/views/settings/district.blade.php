@extends('layouts.master')

@section('title')
Districts
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Districts Management</h5>
            <div class="card-tools">
                @can('setting-district-add')
                    <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'district-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                        Add District
                    </button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('district-table')
        </div>
    </div>
@endsection
