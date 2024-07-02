@extends('layouts.master')

@section('title', 'Verification Assessments')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Verification Assessments
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                @can('verification-view-all')
                    <a href="#all-verifications" class="nav-item nav-link font-weight-bold active">All Assessments</a>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <a href="#domestic-verifications" class="nav-item nav-link font-weight-bold">Domestic Assessments (DTD)</a>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <a href="#lto-verifications" class="nav-item nav-link font-weight-bold ">Large Taxpayers Assessments (LTD)</a>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <a href="#ntr-verifications" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Assessments (NTRD)</a>
                @endcan
                @can('verification-view-pemba')
                    <a href="#pemba-verifications" class="nav-item nav-link font-weight-bold">Pemba Assessments</a>
                @endcan
            </nav>
            <div class="tab-content border border-top-0">
                @can('verification-view-all')
                    <div id="all-verifications" class="tab-pane fade active show p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.assessments-table" />
                        <livewire:verifications.assessments-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <div id="domestic-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.d-t-d-assessments-table" />
                        <livewire:verifications.d-t-d-assessments-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <div id="lto-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.l-t-o-assessments-table" />
                        <livewire:verifications.l-t-o-assessments-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <div id="ntr-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.n-t-r-assessments-table" />
                        <livewire:verifications.n-t-r-assessments-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-pemba')
                    <div id="pemba-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.pemba-assessments-table" />
                        <livewire:verifications.pemba-assessments-table status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
