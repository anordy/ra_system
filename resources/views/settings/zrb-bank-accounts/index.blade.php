@extends('layouts.master')

@section('title')
    ZRB Bank Accounts
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="text-uppercase">ZRB Bank Account Management</h5>
            <div class="card-tools">
                @can('zrb-bank-account-add')
                    <button class="btn btn-info btn-sm"
                    onclick="Livewire.emit('showModal', 'settings.zrb-banks.zrb-bank-account-add-modal')"><i
                        class="fa fa-plus-circle"></i>
                    Add</button>
                @endcan
            </div>
        </div>

        <div class="card-body">
            @livewire('settings.zrb-banks.zrb-bank-account-table')
        </div>
    </div>
@endsection
