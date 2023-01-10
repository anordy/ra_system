@extends('layouts.master')

@section('title')
    Transaction Fees
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">Transaction Fees</h5>
            <div class="card-tools">
                @can('setting-transaction-fees-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'transaction-fees-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('transaction-fees-table')
        </div>
    </div>
@endsection
