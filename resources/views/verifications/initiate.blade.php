@extends('layouts.master')

@section('title', 'Initiate Tax Return Verifications')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Initiate Tax Returns Verifications
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                @can('verification-view-all')
                    <a href="#all-verifications" class="nav-item nav-link font-weight-bold active">All Tax Returns</a>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <a href="#domestic-verifications" class="nav-item nav-link font-weight-bold">Domestic Returns (DTD)</a>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <a href="#lto-verifications" class="nav-item nav-link font-weight-bold ">Large Taxpayers Returns (LTD)</a>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <a href="#ntr-verifications" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Returns (NTRD)</a>
                @endcan
                @can('verification-view-pemba')
                    <a href="#pemba-verifications" class="nav-item nav-link font-weight-bold">Pemba Returns</a>
                @endcan
            </nav>
            <div class="tab-content border border-top-0">
                @can('verification-view-all')
                    <div id="all-verifications" class="tab-pane fade active show p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.unverified-returns-table" />
                        <livewire:verifications.unverified-returns-table vetted="true" department="ALL" status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-domestic-taxpayers')
                    <div id="domestic-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.unverified-returns-table" />
                        <livewire:verifications.unverified-returns-table vetted="true" department="{{ \App\Models\Region::DTD }}" status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-lto-taxpayers')
                    <div id="lto-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.unverified-returns-table" />
                        <livewire:verifications.unverified-returns-table vetted="true" department="{{ \App\Models\Region::LTD }}" status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-non-tax-revenue-taxpayers')
                    <div id="ntr-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.unverified-returns-table" />
                        <livewire:verifications.unverified-returns-table vetted="true" department="{{ \App\Models\Region::NTRD }}" status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
                @can('verification-view-pemba')
                    <div id="pemba-verifications" class="tab-pane fade p-3">
                        <livewire:verifications.verifications-filter tablename="verifications.unverified-returns-table" />
                        <livewire:verifications.unverified-returns-table vetted="true" department="{{ \App\Models\Region::PEMBA }}" status="{{ \App\Enum\TaxVerificationStatus::PENDING }}" />
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
