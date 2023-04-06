@extends('layouts.master')

@section('title')
    Transaction Fees
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            Transaction Fees
            <div class="card-tools">
                @can('setting-transaction-fees-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-primary btn-sm"
                    onclick="Livewire.emit('showModal', 'transaction-fees-add-modal')"><i
                        class="bi bi-plus-circle-fill pr-1"></i>
                    Add Fee</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('transaction-fees-table')
        </div>
    </div>
@endsection
