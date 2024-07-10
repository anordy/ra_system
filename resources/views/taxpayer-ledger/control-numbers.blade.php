@extends('layouts.master')

@section('title', 'Control Numbers')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            {{ __('Tax Payment Control Numbers') }}
        </div>

        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold d-none">Normal Return Debts</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold d-none">Normal Return Debts</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold d-none">Normal Return Debts</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold active">Overdue Return Debts</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade m-2 show d-none">
                    @livewire('taxpayer-ledger.control-numbers-table')
                </div>
                <div id="tab1" class="tab-pane fade m-2 show active">
                    @livewire('taxpayer-ledger.control-numbers-table')
                </div>
            </div>
        </div>

    </div>
@endsection
