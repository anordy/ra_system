@extends('layouts.master')

@section('title', 'Return Debts')

@section('content')
    <div class="card p-0 m-0">
        <div class="card-header text-uppercase font-weight-bold">
            Return Debts
        </div>
        <div class="card-body mt-0 p-2">
            <nav class="nav nav-tabs mt-0 border-top-0">
{{--                <a href="#tab3" class="nav-item nav-link font-weight-bold ">Late fillers</a>--}}
                <a href="#tab2" class="nav-item nav-link font-weight-bold d-none">Normal Return Debts</a>
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Overdue Return Debts</a>
            </nav>
            <div class="tab-content px-2 card pt-3 pb-2">
{{--                <div id="tab3" class="tab-pane fade m-2">--}}
{{--                    <livewire:debt.late-debts-table />--}}

{{--                </div>--}}
                <div id="tab2" class="tab-pane fade m-2 show d-none">
                    <livewire:debt.return-debts-table />

                </div>
                <div id="tab1" class="tab-pane fade m-2 show active">
                    <livewire:debt.return-overdue-debts-table />

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