@extends('layouts.master')

@section('title', 'Normal Debts')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Normal Debts
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.debts-table />
        </div>
    </div>
@endsection
