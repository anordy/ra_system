@extends('layouts.master')

@section('title')
    Banks
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Banks</h5>
            <div class="card-tools">
                @can('setting-bank-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'bank-add-modal')">
                            <i class="bi bi-plus-circle-fill"></i>Add
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('banks-table')
        </div>
    </div>
@endsection
