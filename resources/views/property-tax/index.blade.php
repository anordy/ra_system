@extends('layouts.master')

@section('title')
    Properties
@endsection

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold bg-white">
            Properties
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Registered Properties</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Pending Approval</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Pending Correction</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2">
                <div id="tab1" class="tab-pane fade m-2 show active">
                    @livewire('property-tax.property-tax-table', ['status' => \App\Enum\PropertyStatus::APPROVED])
                </div>
                <div id="tab2" class="tab-pane fade m-2">
                    @livewire('property-tax.property-tax-table', ['status' => \App\Enum\PropertyStatus::PENDING])
                </div>
                <div id="tab3" class="tab-pane fade m-2">
                    @livewire('property-tax.property-tax-table', ['status' => \App\Enum\PropertyStatus::CORRECTION])
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
