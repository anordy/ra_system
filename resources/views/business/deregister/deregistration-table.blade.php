@extends('layouts.master')

@section('title','Business De-registrations History')

@section('content')
    <div class="card">
        <div class="card-header text-uppercase font-weight-bold">
            Business De-registrations
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab2" class="nav-item nav-link font-weight-bold active">Pending Approval</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold">Approved De-registrations</a>
                <a href="#tab3" class="nav-item nav-link font-weight-bold">Rejected De-registrations</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab2" class="tab-pane fade active show m-2">
                    @livewire('business.deregister.pending-deregister-business-table')
                </div>
                <div id="tab1" class="tab-pane fade m-2">
                    @livewire('business.deregister.approved-deregister-business-table')
                </div>

                <div id="tab3" class="tab-pane fade m-2">
                    @livewire('business.deregister.rejected-deregister-business-table')
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