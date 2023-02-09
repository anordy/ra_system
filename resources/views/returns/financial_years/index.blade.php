@extends('layouts.master')

@section('title', 'Financial Years')

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Financial Year Configuration
            @can('setting-financial-year-add')
                <div class="card-tools">
                    <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'returns.financial-years.add-year-modal')"><i
                                class="bi bi-plus-circle-fill pr-1"></i> Add Financial Year
                    </button>
                </div>
            @endcan
        </div>

        <div class="card-body">
            @livewire('returns.financial-years.financial-years-table')
        </div>
    </div>
@endsection