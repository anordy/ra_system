@extends('layouts.master')

@section('title','Sponsor')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Sponsor</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-sponsor-add-modal')"><i
                    class="bi bi-plus-circle-fill"></i>
                Add</button>
            </div>
        </div>
        <div class="card-body">
            @livewire('relief.relief-sponsor-table')
        </div>
    </div>
@endsection