@extends('layouts.master')

@section('title', 'Tax Returns Vetting')

@section('content')

    @php
        $vettingStatus = \App\Enum\VettingStatus::VETTED;
    @endphp

    <div class="card rounded-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Tax Returns Vetting
        </div>

        <div class="card-body">
            <nav class="nav nav-tabs justify-content-between mt-0 border-top-0">
                @can('tax-returns-vetting-view-domestic-taxpayers')
                    <a href="#domestic-tax-payers" class="nav-item nav-link font-weight-bold active">Domestic Taxes Returns
                        (DTD)</a>
                @endcan
                @can('tax-returns-vetting-view-lto-taxpayers')
                    <a href="#large-tax-payers" class="nav-item nav-link font-weight-bold ">Large Taxpayers Returns (LTD)</a>
                @endcan
                @can('tax-returns-vetting-view-non-tax-revenue-taxpayers')
                    <a href="#non-tax-revenues" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Returns (NTRD)</a>
                @endcan
                @can('tax-returns-vetting-view-domestic-taxpayers')
                    <a href="#pemba" class="nav-item nav-link font-weight-bold">Pemba</a>
                @endcan
                    <a href="#uncategorized" class="nav-item nav-link font-weight-bold">Uncategorized</a>
            </nav>
            <br>
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                @can('tax-returns-vetting-view-domestic-taxpayers')
                    <div id="domestic-tax-payers" class="tab-pane fade active show p-2">
                        <div class="p-2">
                            @livewire('vetting.vetting-filter', ['tablename' => 'vetting.vetting-approval-table']) <br>
                            <livewire:vetting.vetting-approval-table-dtd vettingStatus="{{ $vettingStatus }}" />
                        </div>
                    </div>
                @endcan
                @can('tax-returns-vetting-view-domestic-taxpayers')
                    <div id="pemba" class="tab-pane fade show p-2">
                        <div class="p-2">
                            @livewire('vetting.vetting-filter', ['tablename' => 'vetting.vetting-approval-table-pemba']) <br>
                            <livewire:vetting.vetting-approval-table-pemba vettingStatus="{{ $vettingStatus }}" />
                        </div>
                    </div>
                @endcan
                @can('tax-returns-vetting-view-lto-taxpayers')
                    <div id="large-tax-payers" class="tab-pane fade p-2">
                        @livewire('vetting.vetting-filter', ['tablename' => 'vetting.vetting-approval-table-lto']) <br>
                        <livewire:vetting.vetting-approval-table-lto vettingStatus="{{ $vettingStatus }}" />
                    </div>
                @endcan
                @can('tax-returns-vetting-view-non-tax-revenue-taxpayers')
                    <div id="non-tax-revenues" class="tab-pane fade p-2">
                        <div class="p-2">
                            @livewire('vetting.vetting-filter', ['tablename' => 'vetting.vetting-approval-table-ntl']) <br>
                            <livewire:vetting.vetting-approval-table-ntl vettingStatus="{{ $vettingStatus }}" />
                        </div>
                    </div>
                @endcan
                    <div id="uncategorized" class="tab-pane fade p-2">
                        <div class="p-2">
                            @livewire('vetting.vetting-filter', ['tablename' => 'vetting.vetting-approval-table']) <br>
                            <livewire:vetting.vetting-approval-table vettingStatus="{{ $vettingStatus }}"/>
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
