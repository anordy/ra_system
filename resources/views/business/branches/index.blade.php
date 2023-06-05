@extends('layouts.master')

@section('title')
    Business Branches
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Business Branches
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab2" class="nav-item nav-link font-weight-bold active">All Branches</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Approval Progress</a>
                <a href="#tab4" class="nav-item nav-link font-weight-bold">Approval Correction</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2">
                <div id="tab2" class="tab-pane fade m-2 show active">
                    <livewire:business.branches-table status='approved'></livewire:business.branches-table>
                </div>
                <div id="tab1" class="tab-pane fade m-2">
                    @livewire('business.branches-approval-table')
                </div>
                <div id="tab3" class="tab-pane fade m-2">
                    @livewire('business.branches-approval-progress-table')
                </div>
                <div id="tab4" class="tab-pane fade m-2">
                    @livewire('business.branches-approval-correction-table',['status' => 'correction'])
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
