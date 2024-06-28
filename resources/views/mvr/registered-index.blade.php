@extends('layouts.master')

@section('title', 'Motor Vehicle Registration')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicles</h5>
        </div>

        <div class="card-body">
            <livewire:mvr.registered-motor-vehicles-table />
        </div>
    </div>
@endsection

