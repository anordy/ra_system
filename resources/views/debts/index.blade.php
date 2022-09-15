@extends('layouts.master')

@section('title', 'Return Debts')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Return Debts
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.return-debts-table />
        </div>
    </div>
@endsection
