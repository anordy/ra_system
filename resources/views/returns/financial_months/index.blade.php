@extends('layouts.master')

@section('title', 'Financial Months')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">Financial Months Configuration</div>
            <div class="card-tools">
                    <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'returns.financial-months.add-month-modal')"><i
                                class="fa fa-plus-circle"></i> Add
                    </button>

            </div>
        </div>

        <div class="card-body">
            @livewire('returns.financial-months.financial-months-table')
        </div>
    </div>
@endsection