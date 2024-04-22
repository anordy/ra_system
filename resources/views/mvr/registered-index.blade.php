@extends('layouts.master')

@section('title', 'Motor Vehicle Registration')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicles</h5>
            <div class="card-tools">
                @can('mvr_initiate_registration')
                    <button class="btn btn-info btn-sm"><i
                                class="fa fa-plus-circle"></i>
                        Register Motor Vehicle</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            <livewire:mvr.registered-motor-vehicles-table />
        </div>
    </div>
@endsection

