@extends('layouts.master')

@section('title')
    Countries
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Countries Management
            <div class="card-tools">
                @can('setting-country-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm mx-3" onclick="Livewire.emit('showModal', 'country-add-modal')">
                            <i class="bi bi-plus-circle-fill pr-2"></i>
                            Add new country
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('country-table')
        </div>
    </div>
@endsection
