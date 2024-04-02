@extends('layouts.master')

@section('title', 'Tax Refunds')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Refunds on Importation
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                <a href="#all-approval" class="nav-item nav-link font-weight-bold active">All Tax Refunds</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all-approval" class="tab-pane fade active show">
                    <livewire:tax-refund.tax-refund-table/>
                </div>

            </div>
        </div>
    </div>

@endsection