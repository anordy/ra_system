@extends('layouts.master')

@section('title')
    Hotel Returns Configuration
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Hotel Returns Configuration</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'settings.hotel-levy-returns.hotel-levy-config-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.hotel-levy-returns.hotel-levy-config-table')
        </div>
    </div>
@endsection
