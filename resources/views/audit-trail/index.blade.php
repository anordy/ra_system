@extends('layouts.master')

@section('title')
    Audit Trail
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="text-uppercase font-weight-bold">System Audit Trail Logs</div>
        </div>

        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Audit Logs</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">User Audits</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show card p-2">
                    @livewire('audit-trail.audit-log-table')
                </div>
                <div id="tab2" class="tab-pane fade card p-2">
                    @livewire('audit-trail.users-audit-trail-table')
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
