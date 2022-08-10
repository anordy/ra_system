@extends('layouts.master')

@section('title','Ministries')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Ministries</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-ministries-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>
        <div class="card-body">
            @livewire('relief.relief-ministries-table')
        </div>
    </div>
@endsection