@extends('layouts.master')

@section('title','Rejected Tax Claims')

@section('content')
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#dtr" class="nav-item nav-link font-weight-bold active">Domestic Taxes Returns (DTD)</a>
                <a href="#lto" class="nav-item nav-link font-weight-bold">Large Taxpayers Returns (LTD)</a>
                <a href="#ntr" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Returns (NTRD)</a>
                <a href="#pemba" class="nav-item nav-link font-weight-bold">Pemba</a>
                <a href="#uncategorized" class="nav-item nav-link font-weight-bold">All</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="dtr" class="tab-pane fade active show  p-2">
                    <livewire:claims.rejected.d-t-r-claims-table />
                </div>
                <div id="lto" class="tab-pane fade  p-2">
                    <livewire:claims.rejected.l-t-o-claims-table />
                </div>
                <div id="ntr" class="tab-pane fade  p-2">
                    <livewire:claims.rejected.n-t-r-claims-table />
                </div>
                <div id="pemba" class="tab-pane fade  p-2">
                    <livewire:claims.rejected.pemba-claims-table />
                </div>
                <div id="uncategorized" class="tab-pane fade  p-2">
                    <livewire:claims.uncategorized-claims-table status="{{ \App\Enum\TaxClaimStatus::REJECTED  }}" />
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