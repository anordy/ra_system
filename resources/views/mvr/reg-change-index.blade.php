@extends('layouts.master')

@section('title', 'Registration Change Requests')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Requests</h5>
            <div class="card-tools">
                @can('mvr_initiate_registration_change')
                    New Requests
                @endcan
            </div>
        </div>

        <div class="card-body">
            <livewire:mvr.registration-change-requests-table />
        </div>
    </div>
@endsection

