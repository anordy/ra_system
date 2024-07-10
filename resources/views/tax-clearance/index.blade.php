@extends('layouts.master')

@section('title')
    Tax Clearance
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Clearance Request
        </div>
        <div class="card-body mt-0 p-2">

            <ul class="nav nav-tabs shadow-sm" id="myPurchaseTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active font-weight-bold" id="ldt-tab"
                       data-toggle="tab" href="#ltd" role="tab"
                       aria-controls="standard-supplies-list"
                       aria-selected="true">{{ __('Large Taxpayer Department') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link font-weight-bold" id="dtd-tab"
                       data-toggle="tab" href="#dtd" role="tab"
                       aria-controls="standard-supplies-list"
                       aria-selected="true">{{ __('Domestic Tax Department') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link font-weight-bold" id="ntrd-tab"
                       data-toggle="tab" href="#ntrd" role="tab"
                       aria-controls="standard-supplies-list"
                       aria-selected="true">{{ __('Non Tax Revenue Department') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link font-weight-bold" id="pemba-tab"
                       data-toggle="tab" href="#pemba" role="tab"
                       aria-controls="standard-supplies-list"
                       aria-selected="true">{{ __('Pemba') }}</a>
                </li>
            </ul>

            <div class="tab-content bg-white border shadow-sm" id="myPurchaseTab">
                <div class="py-4 px-2 tab-pane fade show active" id="ltd"
                     role="tabpanel" aria-labelledby="ltd-tab">
                    <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                        <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                            <a href="#all-approval-ltd" class="nav-item nav-link font-weight-bold active">All Clearance</a>
                            <a href="#approved-approval-ltd" class="nav-item nav-link font-weight-bold">Approved Clearance</a>
                            <a href="#rejected-approval-ltd" class="nav-item nav-link font-weight-bold">Rejected Clearance</a>
                        </nav>
                        <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                            <div id="all-approval-ltd" class="tab-pane fade active show">
                                <livewire:tax-clearance.tax-clearance-request-approval-table department="{{ \App\Models\Region::LTD  }}" />
                            </div>
                            <div id="approved-approval-ltd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::APPROVED, 'department' => \App\Models\Region::LTD])
                            </div>
                            <div id="rejected-approval-ltd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::REJECTED, 'department' => \App\Models\Region::LTD])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-2 tab-pane fade show" id="dtd"
                     role="tabpanel" aria-labelledby="dtd-tab">
                    <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                        <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                            <a href="#all-approval-dtd" class="nav-item nav-link font-weight-bold active">All Clearance</a>
                            <a href="#approved-approval-dtd" class="nav-item nav-link font-weight-bold">Approved Clearance</a>
                            <a href="#rejected-approval-dtd" class="nav-item nav-link font-weight-bold">Rejected Clearance</a>
                        </nav>

                        <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                            <div id="all-approval-dtd" class="tab-pane fade active show">
                                <livewire:tax-clearance.tax-clearance-request-approval-table department="{{ \App\Models\Region::DTD }}" />
                            </div>
                            <div id="approved-approval-dtd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::APPROVED, 'department' => \App\Models\Region::DTD])
                            </div>
                            <div id="rejected-approval-dtd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::REJECTED, 'department' => \App\Models\Region::DTD])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-2 tab-pane fade show" id="ntrd"
                     role="tabpanel" aria-labelledby="ntrd-tab">
                    <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                        <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                            <a href="#all-approval-ntrd" class="nav-item nav-link font-weight-bold active">All Clearance</a>
                            <a href="#approved-approval-ntrd" class="nav-item nav-link font-weight-bold">Approved Clearance</a>
                            <a href="#rejected-approval-ntrd" class="nav-item nav-link font-weight-bold">Rejected Clearance</a>
                        </nav>

                        <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                            <div id="all-approval-ntrd" class="tab-pane fade active show">
                                <livewire:tax-clearance.tax-clearance-request-approval-table department="{{ \App\Models\Region::NTRD }}" />
                            </div>
                            <div id="approved-approval-ntrd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::APPROVED, 'department' => \App\Models\Region::NTRD])
                            </div>
                            <div id="rejected-approval-ntrd" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::REJECTED, 'department' => \App\Models\Region::NTRD])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="py-4 px-2 tab-pane fade show" id="pemba"
                     role="tabpanel" aria-labelledby="pemba-tab">
                    <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                        <nav class="nav nav-tabs mt-0 border-top-0 mb-3">
                            <a href="#all-approval-pemba" class="nav-item nav-link font-weight-bold active">All Clearance</a>
                            <a href="#approved-approval-pemba" class="nav-item nav-link font-weight-bold">Approved Clearance</a>
                            <a href="#rejected-approval-pemba" class="nav-item nav-link font-weight-bold">Rejected Clearance</a>
                        </nav>

                        <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                            <div id="all-approval-pemba" class="tab-pane fade active show">
                                <livewire:tax-clearance.tax-clearance-request-approval-table department="{{ \App\Models\Region::PEMBA }}" />
                            </div>
                            <div id="approved-approval-pemba" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::APPROVED, 'department' => \App\Models\Region::PEMBA])
                            </div>
                            <div id="rejected-approval-pemba" class="tab-pane fade">
                                @livewire('tax-clearance.tax-clearance-request-table', ['status' => App\Enum\TaxClearanceStatus::REJECTED, 'department' => \App\Models\Region::PEMBA])
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $(".nav-tabs a").click(function() {
                $(this).tab('show');
            });
        });
    </script>
@endsection
