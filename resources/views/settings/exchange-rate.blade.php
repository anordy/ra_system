@extends('layouts.master')

@section('title')
    Exchange Rate
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Exchange Rate Management
            <div class="card-tools">
                @can('setting-exchange-rate-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm px-3"
                                onclick="Livewire.emit('showModal', 'exchange-rate-add-modal')"><i
                                    class="bi bi-plus-circle-fill pr-1"></i>
                            Add New Rate
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
