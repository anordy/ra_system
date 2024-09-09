@extends('layouts.master')

@section('title','Tasks')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Tasks</h5>
            <div class="card-tools">
                @can('setting-user-add')
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'report-register.task.create-task')"><i
                            class="bi bi-plus-circle-fill"></i>
                        New Task
                    </button>
                @endcan
            </div>
        </div>
        <div class="card-body">
            @livewire('report-register.task.task-table')
        </div>
    </div>
@endsection
