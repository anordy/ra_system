@extends('layouts.master')

@section('title')
    Education Level
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Education Level</h5>
            <div class="card-tools">
                @can('setting-education-level-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-info btn-sm"
                                onclick="Livewire.emit('showModal', 'education-level-add-modal')"><i
                                    class="fa fa-plus-circle"></i>
                            Add
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('education-level-table')
        </div>
    </div>
@endsection
