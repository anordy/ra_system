@extends('layouts.master')

@section('title')
    Bank Account
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            Bank Account
            <div class="card-tools">
                @can('setting-bank-add')
                    @if(approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                                onclick="Livewire.emit('showModal', 'bank-account-add-modal')">
                            <i class="bi bi-plus-square-fill mr-1"></i>Add New Account
                        </button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('bank-accounts-table')
        </div>
    </div>
@endsection
