@extends("layouts.master")
@php use App\Models\Region; @endphp

@section("title", "Audit Approved")

@section("content")
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audits Approved
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs justify-content-between my-3 flex-nowrap" role="tablist">
                <a href="#domestic-tax-payers" class="nav-item nav-link font-weight-bold active" data-toggle="tab">Domestic Tax Department (DTD)</a>
                <a href="#large-tax-payers" class="nav-item nav-link font-weight-bold" data-toggle="tab">Large Taxpayers Department (LTD)</a>
                <a href="#non-tax-revenues" class="nav-item nav-link font-weight-bold" data-toggle="tab">Non-Tax Revenue Department (NTRD)</a>
                <a href="#pemba" class="nav-item nav-link font-weight-bold" data-toggle="tab">Pemba</a>
            </nav>
            <br>
            <div class="tab-content">
                <div class="tab-pane p-2 fade show active" id="domestic-tax-payers" role="tabpanel"
                    aria-labelledby="domestic-tax-payers-tab">
                    @livewire("audit.tax-audit-verified-table", ["taxRegion" => Region::DTD])
                </div>

                <div class="tab-pane p-2 fade" id="large-tax-payers" role="tabpanel"
                    aria-labelledby="large-tax-payers-tab">
                    @livewire("audit.tax-audit-verified-table", ["taxRegion" => Region::LTD])
                </div>

                <div class="tab-pane p-2 fade" id="non-tax-revenues" role="tabpanel"
                    aria-labelledby="non-tax-revenues-tab">
                    @livewire("audit.tax-audit-verified-table", ["taxRegion" => Region::NTRD])
                </div>
                <div class="tab-pane p-2 fade" id="pemba" role="tabpanel" aria-labelledby="pemba-tab">
                    @livewire("audit.tax-audit-verified-table", ["taxRegion" => Region::PEMBA])
                </div>
            </div>
        </div>
    </div>
@endsection
