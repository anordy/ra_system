@extends('layouts.master')

@section('title','Reliefs Projects')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Projects List For {{ $project->name }}</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-project-list-add-modal', {{$project->id}})"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>
        <div class="card-body">
            @livewire('relief.relief-project-list-table')
        </div>
    </div>
@endsection