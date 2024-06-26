@extends('layouts.master')

@section('title','Land Lease Approvals')

@section('content')
    <div class="rounded-0">
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">{{ __('Lease Registration Approvals')
                }}</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold ">{{ __('Lease Partial Payments
                Approvals')}}</a>
                @can('land-lease-approve-currency-change-application')
                    <a href="#tab3"
                       class="nav-item nav-link font-weight-bold">{{ __('Lease Currency Change Approvals') }}</a>
                @endcan

            </nav>
            <div class="tab-content px-2 card mb-1 shadow-none border rounded-0">
                <div id="tab1" class="tab-pane fade m-2 show active">
                    <div>
                        <livewire:land-lease.land-lease-registration-approve-list/>
                    </div>
                </div>
                <div id="tab2" class="tab-pane fade m-2 show">
                    <div>
                        <livewire:land-lease.land-lease-approve-list/>
                    </div>
                </div>
                @can('land-lease-approve-currency-change-application')
                    <div id="tab3" class="tab-pane fade m-2">
                        <livewire:land-lease.lease-currency-approve-list/>
                    </div>
                @endcan
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