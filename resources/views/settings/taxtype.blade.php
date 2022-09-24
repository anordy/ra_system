@extends('layouts.master')

@section('title')
    Tax Types
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Tax Types</h5>
            <div class="card-tools">
                @can('setting-tax-type-add')
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'tax-type-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('tax-types-table')
        </div>
    </div>
@endsection
