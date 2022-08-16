@extends('layouts.master')

@section('title', 'Motor Vehicle Registration')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Motor Vehicles</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.chassis-number-search','mvr.chassis-search')"><i
                            class="fa fa-plus-circle"></i>
                    New Motor Vehicle</button>
            </div>
        </div>

        <div class="card-body">
            <livewire:mvr.motor-vehicles-table />

        </div>
    </div>
@endsection

