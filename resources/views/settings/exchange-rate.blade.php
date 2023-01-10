@extends('layouts.master')

@section('title')
    Exchange Rate
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Exchange Rate Management</h5>
            <div class="card-tools">
                @can('setting-exchange-rate-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'exchange-rate-add-modal')"><i
                                    class="fa fa-plus-circle"></i>
                            Add
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('exchange-rate-table')
        </div>
    </div>
@endsection
