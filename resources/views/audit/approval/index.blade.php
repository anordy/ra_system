@extends("layouts.master")

@section("title", "Tax Audits Approvals")
@php use App\Models\Region; @endphp

@section("content")
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Audit Approvals
            <div class="card-tools">
                <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'audit.business-audit-add-modal')">
                    <i class="fa fa-plus-circle"></i>
                    Add To Audit
                </button>
            </div>
        </div>
        <div class="card-body">
            @livewire("approval.approval-count-card", ["modelName" => "TaxAudit"])

            <ul class="nav nav-tabs justify-content-between" role="tablist">
                <li class="nav-item font-weight-bold">
                    <a class="nav-link active" data-toggle="tab" href="#businesses-added-to-audit" role="tab"
                        aria-selected="true">Businesses Added to Audit</a>
                </li>
                <li class="nav-item font-weight-bold">
                    <a class="nav-link" data-toggle="tab" href="#pending-approval" role="tab"
                        aria-selected="false">Pending Approval</a>
                </li>
                <li class="nav-item font-weight-bold">
                    <a class="nav-link" data-toggle="tab" href="#approval-progress" role="tab"
                        aria-selected="false">Approval Progress</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane py-2 px-1 fade show active" id="businesses-added-to-audit" role="tabpanel"
                    aria-labelledby="businesses-added-to-audit-tab">
                    <nav class="nav nav-tabs justify-content-between my-3 flex-nowrap" role="tablist">
                        <a href="#domestic-tax-payers" class="nav-item nav-link font-weight-bold active" data-toggle="tab">Domestic Tax Department
                            (DTD)</a>
                        <a href="#large-tax-payers" class="nav-item nav-link font-weight-bold" data-toggle="tab">Large Taxpayers Department (LTD)</a>
                        <a href="#non-tax-revenues" class="nav-item nav-link font-weight-bold" data-toggle="tab">Non-Tax Revenue Department (NTRD)</a>
                        <a href="#pemba" class="nav-item nav-link font-weight-bold" data-toggle="tab">Pemba</a>
                    </nav>
                    <br>
                    <div class="tab-content">
                        <div class="tab-pane py-2 px-1 fade show active" id="domestic-tax-payers" role="tabpanel"
                            aria-labelledby="domestic-tax-payers-tab">
                            @livewire("audit.tax-audit-initiate-table", ["taxRegion" => Region::DTD])
                        </div>

                        <div class="tab-pane py-2 px-1 fade" id="large-tax-payers" role="tabpanel"
                            aria-labelledby="large-tax-payers-tab">
                            @livewire("audit.tax-audit-initiate-table", ["taxRegion" => Region::LTD])
                        </div>

                        <div class="tab-pane py-2 px-1 fade" id="non-tax-revenues" role="tabpanel"
                            aria-labelledby="non-tax-revenues-tab">
                            @livewire("audit.tax-audit-initiate-table", ["taxRegion" => Region::NTRD])
                        </div>
                        <div class="tab-pane py-2 px-1 fade" id="pemba" role="tabpanel" aria-labelledby="pemba-tab">
                            @livewire("audit.tax-audit-initiate-table", ["taxRegion" => Region::PEMBA])
                        </div>
                    </div>
                </div>
                <div class="tab-pane py-2 px-1 fade" id="pending-approval" role="tabpanel" aria-labelledby="pending-approval-tab">
                    @livewire("audit.tax-audit-approval-table")
                </div>
                <div class="tab-pane py-2 px-1 fade" id="approval-progress" role="tabpanel" aria-labelledby="approval-progress-tab">
                    @livewire("audit.tax-audit-approval-progress-table")
                </div>
            </div>
        </div>

    </div>
@endsection
