@extends('layouts.master')

@section('title', 'Registration Change Requests')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Requests</h5>
            <div class="card-tools">
                @can('mvr_initiate_registration_change')
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','mvr.internal-search')"><i
                            class="bi bi-plus-circle-fill"></i>
                    New Requests</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <livewire:mvr.registration-change-requests-table />
        </div>
    </div>
@endsection

