@extends('layouts.master')

@section('title')
    Business Branches
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Business Branches
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab2" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold">Approved Branches</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab2" class="tab-pane fade m-2 show active">
                    <livewire:business.branches-table status='pending'></livewire:business.branches-table>
                </div>
                <div id="tab1" class="tab-pane fade m-2">
                    <livewire:business.branches-table status='approved'></livewire:business.branches-table>
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

