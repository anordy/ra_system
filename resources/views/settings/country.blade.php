@extends('layouts.master')

@section('title')
    Roles
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Countries Management</h5>
            <div class="card-tools">
                @can('setting-country-add')
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'country-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('country-table')
        </div>
    </div>
@endsection
