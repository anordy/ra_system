@extends('layouts.master')

@section('title')
    Business Changes Requests
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Business Changes Requests
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Confirmed Changes</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Changes</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold">Pending Correction</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    <livewire:business.updates.changes-request-table status='pending'></livewire:business.updates.changes-request-table>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:business.updates.changes-request-table status='approved'></livewire:business.updates.changes-request-table>
                </div>
                <div id="tab3" class="tab-pane fade">
                    <livewire:business.updates.changes-request-table status='rejected'></livewire:business.updates.changes-request-table>
                </div>
                <div id="tab4" class="tab-pane fade">
                    <livewire:business.updates.changes-request-table status='correction'></livewire:business.updates.changes-request-table>
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

