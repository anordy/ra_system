@extends('layouts.master')

@section('title', 'Taxpayer Ledgers')


@section('content')

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            Taxpayer Ledgers
        </div>
        <div class="card-body">
            <livewire:finance.taxpayer-ledger-table />
        </div>
    </div>
@endsection
