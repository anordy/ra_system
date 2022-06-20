@extends('layouts.master')

@section('title')
    Business
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Registered Businesses</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'business.registration-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('business.registrations-table')
        </div>
    </div>
@endsection
