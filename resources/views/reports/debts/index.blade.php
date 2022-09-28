@extends('layouts.master')

@section('title', 'Managerial Reports')

@section('content')
    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Debt Reports
        </div>
        <div class="card-body mt-0">
            @livewire('reports.debts.debt-report')
        </div>
    </div>
@endsection
