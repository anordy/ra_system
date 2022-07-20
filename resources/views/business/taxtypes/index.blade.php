@extends('layouts.master')

@section('title')
    Change of Tax Types Requests
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Change of Tax Types Requests
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Confirmed Changes</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected Changes</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    <livewire:business.tax-type.tax-type-table status='pending'></livewire:business.tax-type.tax-type-table>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:business.tax-type.tax-type-table status='approved'></livewire:business.tax-type.tax-type-table>
                </div>
                <div id="tab3" class="tab-pane fade">
                    <livewire:business.tax-type.tax-type-table status='rejected'></livewire:business.tax-type.tax-type-table>
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

