@extends('layouts.master')

@section('title')
    PBZ Statements
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            PBZ Statements
            <div class="card-tools">
                <button class="btn btn-primary" onclick="Livewire.emit('showModal', 'payments.p-b-z-statement-request-modal')">
                    <i class="bi bi-send-plus-fill mr-1"></i> Request New Statement
                </button>
            </div>
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('payments.p-b-z-statement-filter', ['tablename' => 'pbz-statements-table']) <br>
            <livewire:payments.p-b-z-statements-table status='pending'></livewire:payments.p-b-z-statements-table>
        </div>
    </div>
@endsection