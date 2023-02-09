@extends('layouts.master')

@section('title')
    ZRA Bank Accounts
@endsection

@section('content')
    <div class="card">
        <div class="card-header bg-white text-uppercase font-weight-bold">
            ZRB Bank Account Management
            <div class="card-tools">
                @can('zrb-bank-account-add')
                    @if (approvalLevel(Auth::user()->level_id, 'Maker'))
                        <button class="btn btn-primary btn-sm"
                            onclick="Livewire.emit('showModal', 'settings.zrb-banks.zrb-bank-account-add-modal')"><i
                                class="bi bi-plus-circle-fill pr-1"></i>
                            Add Bank Account</button>
                    @endif
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.zrb-banks.zrb-bank-account-table')
        </div>
    </div>
@endsection
