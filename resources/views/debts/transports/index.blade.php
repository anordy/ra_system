@extends('layouts.master')
@section('title', 'Transport Service Debts')
@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Transport Service Debts
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab2" class="nav-item nav-link font-weight-bold active">Normal Transport Service Debts</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold">Overdue Transport Service Debts</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab2" class="tab-pane fade m-2 show active">
                    <livewire:debt.transport-debts-table />
                </div>
                <div id="tab1" class="tab-pane fade m-2">
                    <livewire:debt.transport-overdue-debts-table />
                </div>
            </div>
        </div>
    </div>
@endsection