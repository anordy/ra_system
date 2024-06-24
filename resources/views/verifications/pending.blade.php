@extends('layouts.master')

@section('title', 'Pending Return Verifications')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Pending Tax Returns Verifications
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                @can('verification-view-all')
                    <a href="#all-verifications" class="nav-item nav-link font-weight-bold active">All Verifications</a>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <a href="#domestic-verifications" class="nav-item nav-link font-weight-bold">Domestic Verifications (DTD)</a>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <a href="#lto-verifications" class="nav-item nav-link font-weight-bold ">Large Taxpayers Verifications (LTD)</a>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <a href="#ntr-verifications" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Verifications (NTRD)</a>
                @endcan
                @can('verification-view-pemba')
                    <a href="#pemba-verifications" class="nav-item nav-link font-weight-bold">Pemba Verifications</a>
                @endcan
            </nav>

            <div class="tab-content border border-top-0">
                @can('verification-view-all')
                    <div id="all-verifications" class="tab-pane fade active show p-3">
                        <livewire:verifications.verifications-filter tablename="verification.verifications-table" />
                        <livewire:verifications.verifications-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <div id="domestic-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verification.d-t-d-verifications-table" />
                        <livewire:verifications.d-t-d-verifications-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <div id="lto-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verification.l-t-o-verifications-table" />
                        <livewire:verifications.l-t-o-verifications-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <div id="ntr-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verification.n-t-r-verifications-table" />
                        <livewire:verifications.n-t-r-verifications-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-pemba')
                    <div id="pemba-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verification.pemba-verifications-table" />
                        <livewire:verifications.pemba-verifications-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
