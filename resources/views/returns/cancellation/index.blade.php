@extends('layouts.master')

@section('title',__('Tax Returns Cancellation History'))

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            <div class="d-flex justify-content-between align-items-center bg-white">
                <div class="text-uppercase font-weight-bold">Tax Returns Cancellation
                </div>
            </div>
        </div>

        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all" class="nav-item nav-link font-weight-bold active">Approved</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending</a>
                <a href="#rejected-approval" class="nav-item nav-link font-weight-bold">Rejected</a>
            </nav>

            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all" class="tab-pane fade active show p-2">
                    @livewire('returns.cancellation.cancellation-table', ['status' => \App\Enum\ReturnStatus::APPROVED])
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    @livewire('returns.cancellation.cancellation-table', ['status' => \App\Enum\ReturnStatus::PENDING])
                </div>
                <div id="rejected-approval" class="tab-pane fade p-2">
                    @livewire('returns.cancellation.cancellation-table', ['status' => \App\Enum\ReturnStatus::REJECTED])
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
