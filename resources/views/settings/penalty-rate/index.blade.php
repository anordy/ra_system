@extends('layouts.master')

@section('title')
    Penalty Rate
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Penalty Rate Management</h5>
            <div class="card-tools">
                {{-- @can('setting-exchange-rate-add') --}}
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.penalty-rate.penalty-rate-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                {{-- @endcan --}}
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.penalty-rate.penalty-rates-table')
        </div>
    </div>
@endsection
