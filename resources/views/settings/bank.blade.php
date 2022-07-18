@extends('layouts.master')

@section('title')
    Banks
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Banks</h5>
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'bank-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>

        <div class="card-body">
            @livewire('banks-table')
        </div>
    </div>
@endsection
