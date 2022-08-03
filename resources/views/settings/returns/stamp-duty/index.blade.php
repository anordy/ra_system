@extends('layouts.master')

@section('title')
    Stamp Duty Returns
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h6 class="text-uppercase">Stamp Duty Returns Configurations</h6>
            <div class="card-tools">
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'returns.stamp-duty.add-stamp-duty-modal')">
                    <i class="bi bi-plus-circle-fill"></i>
                    New Service</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('returns.stamp-duty.stamp-duty-services-table')
        </div>
    </div>
@endsection