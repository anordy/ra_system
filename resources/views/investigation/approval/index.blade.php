@extends('layouts.master')

@section('title', 'Investigations Approvals')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Investigations Approvals
            <div class="card-tools">
                <button class="btn btn-info btn-sm"
                onclick="Livewire.emit('showModal', 'relief.relief-project-add-modal')"><i
                    class="fa fa-plus-circle"></i>
                Add</button>
            </div>
        </div>
        <div class="card-body">
            @livewire('investigation.tax-investigation-approval-table')
        </div>
    </div>
@endsection
