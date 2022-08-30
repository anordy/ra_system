@extends('layouts.master')

@section('title', 'Overdue Debts')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Overdue Debts
        </div>
        <div class="card-body mt-0 p-2">
            <livewire:debt.overdue-debts-table />
        </div>
    </div>
@endsection
