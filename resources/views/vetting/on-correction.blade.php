@extends('layouts.master')

@section('title', 'Tax Returns Vetting')

@section('content')

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            On Correction Tax Returns Vetting
        </div>

        <div class="card-body">
        <nav class="nav nav-tabs mt-0border-top-0">
                <a href="#domestic-tax-payers" class="nav-item nav-link font-weight-bold active">Domestic Taxpayers</a>
                <a href="#large-tax-payers" class="nav-item nav-link font-weight-bold ">Large Tax Payers</a>
                <a href="#non-tax-revenues" class="nav-item nav-link font-weight-bold">Non-Tax Revenues</a>
        </nav> <br>
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="domestic-tax-payers" class="tab-pane fade active show p-2">
                    <div class="card p-2">
                    <livewire:vetting.vetting-approval-table vettingStatus="{{ \App\Enum\VettingStatus::CORRECTION }}" />
                    </div>
                </div>
                <div id="large-tax-payers" class="tab-pane fade p-2">
                    <livewire:vetting.vetting-approval-table-lto vettingStatus="{{ \App\Enum\VettingStatus::CORRECTION }}" />
                </div>
                <div id="non-tax-revenues" class="tab-pane fade p-2">
                    <div class="card p-2">
                    <livewire:vetting.vetting-approval-table-ntl vettingStatus="{{ \App\Enum\VettingStatus::CORRECTION }}" />
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
