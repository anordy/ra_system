@extends('layouts.master')

@section('title', 'Financial Years')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">Financial Year Configuration</div>
            <div class="card-tools">
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'returns.financial-years.add-year-modal')"><i
                                class="fa fa-plus-circle"></i> Add
                    </button>

            </div>
        </div>

        <div class="card-body">
            @livewire('returns.financial-years.financial-years-table')
        </div>
    </div>
@endsection