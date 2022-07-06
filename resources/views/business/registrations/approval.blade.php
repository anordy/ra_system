@extends('layouts.master')

@section('title', 'Business Registration Details')

@section('content')

    <div class="card p-0 m-0">

        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Business Information</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Approval History</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    <livewire:approval.approval-processing modelName='App\Models\Business' modelId="{{ $business->id }}" />
                    @include('business.registrations.includes.business_info')
                </div>
                <div id="tab2" class="tab-pane fade">
                    <livewire:approval.approval-history-table modelName='App\Models\Business' modelId="{{ $business->id }}" />
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
