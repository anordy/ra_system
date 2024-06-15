@extends('layouts.master')

@section('title')
    Business Categories
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Business categories</h5>
            <div class="card-tools">
                @can('setting-business-cat-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'business-cat-add-modal')"><i
                                    class="bi bi-plus-circle-fill"></i>
                            Add
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('business-cat-table')
        </div>
    </div>
@endsection
