@extends('layouts.master')

@section('title', 'Tax Claims')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Submitted Tax Claims
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#uncategorized" class="nav-item nav-link font-weight-bold active">All</a>
                <a href="#dtr" class="nav-item nav-link font-weight-bold">Domestic Taxes Returns (DTD)</a>
                <a href="#lto" class="nav-item nav-link font-weight-bold">Large Taxpayers Returns (LTD)</a>
                <a href="#ntr" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Returns (NTRD)</a>
                <a href="#pemba" class="nav-item nav-link font-weight-bold">Pemba</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="uncategorized" class="tab-pane fade active show p-2">
                    <livewire:claims.uncategorized-claims-table status="{{ \App\Enum\TaxClaimStatus::PENDING  }}" />
                </div>
                <div id="dtr" class="tab-pane fade p-2">
                    <livewire:claims.submitted.d-t-r-claims-table />
                </div>
                <div id="lto" class="tab-pane fade  p-2">
                    <livewire:claims.submitted.l-t-o-claims-table />
                </div>
                <div id="ntr" class="tab-pane fade  p-2">
                    <livewire:claims.submitted.n-t-r-claims-table />
                </div>
                <div id="pemba" class="tab-pane fade  p-2">
                    <livewire:claims.submitted.pemba-claims-table />
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