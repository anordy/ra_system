@extends('layouts.master')

@section('title', 'Motor Vehicle - Written Off')

@section('content')
    <div class="card mt-3">
        <h3>Written Off</h3>
        <div class="card-header">
            <div class="card-tools">
                @can('mvr_initiate_de_registration')
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','mvr.internal-search-wo')"><i
                            class="bi bi-plus-circle-fill"></i>
                    New Written off Vehicle</button>
                @endcan
            </div>
        </div>

        <div class="card-body">

            <livewire:mvr.written-off-motor-vehicles-table/>

        </div>
    </div>
@endsection

