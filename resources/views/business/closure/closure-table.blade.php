@extends('layouts.master')

@section('title')
    Business Closures
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Businesses Closures
        </div>
        <div class="card-body mt-0 p-2">
            @livewire('business.closure.pending-closures-table')

            {{-- <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approved Business</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Business</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    @livewire('business.closure.pending-closures-table')
                </div>
                <div id="tab2" class="tab-pane fade">
                    @livewire('business.closure.approved-closures-table')
                </div>
                <div id="tab3" class="tab-pane fade">
                    @livewire('business.closure.rejected-closures-table')
                </div>
            </div> --}}
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
