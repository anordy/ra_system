@extends('layouts.master')

@section('title', 'Motor Vehicle Registration')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5>Requests</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                        onclick="Livewire.emit('showModal', 'mvr.chassis-number-internal-search','mvr.internal-search-dr')"><i
                            class="fa fa-plus-circle"></i>
                    New Requests</button>
            </div>
        </div>

        <div class="card-body">
            <livewire:mvr.de-register-requests-table />
        </div>
    </div>
@endsection

