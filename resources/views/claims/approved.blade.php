@extends('layouts.master')

@section('title','Approved Tax Claims')

@section('content')
    <div class="card">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#dtr" class="nav-item nav-link font-weight-bold active">Domestic Taxes Returns (DTD)</a>
                <a href="#lto" class="nav-item nav-link font-weight-bold">Large Taxpayers Returns (LTD)</a>
                <a href="#ntr" class="nav-item nav-link font-weight-bold">Non-Tax Revenue Returns (NTRD)</a>
                <a href="#uncategorized" class="nav-item nav-link font-weight-bold">Uncategorized</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="dtr" class="tab-pane fade active show  p-2">
                    <livewire:claims.approved.d-t-r-claims-table />
                </div>
                <div id="lto" class="tab-pane fade  p-2">
                    <livewire:claims.approved.l-t-o-claims-table />
                </div>
                <div id="ntr" class="tab-pane fade  p-2">
                    <livewire:claims.approved.n-t-r-claims-table />
                </div>
                <div id="uncategorized" class="tab-pane fade  p-2">
                    <livewire:claims.uncategorized-claims-table />
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