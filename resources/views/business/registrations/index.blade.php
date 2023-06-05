@extends('layouts.master')

@section('title')
    Business Management
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Registered Businesses
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-businesses" class="nav-item nav-link font-weight-bold active">All Businesses</a>
                <a href="#pending-approval" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#approval-progress" class="nav-item nav-link font-weight-bold">Approval Progress</a>
                <a href="#approval-correction" class="nav-item nav-link font-weight-bold">Approval Correction</a>
            </nav>
          
            <div class="tab-content px-2 pt-3 pb-2 border border-top-0">
                <div id="all-businesses" class="tab-pane fade active show p-2">
                    <livewire:business.registrations-table></livewire:business.registrations-table>
                </div>
                <div id="pending-approval" class="tab-pane fade p-2">
                    @livewire('business.registrations-approval-table')
                </div>
                <div id="approval-progress" class="tab-pane fade p-2">
                    @livewire('business.registrations-progress-approval-table')
                </div>
                <div id="approval-correction" class="tab-pane fade p-2">
                    @livewire('business.registrations-approval-correction-table')
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
