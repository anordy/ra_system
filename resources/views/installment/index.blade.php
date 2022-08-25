@extends('layouts.master')

@section('title','Installments')

@section('content')
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installments
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-installments" class="nav-item nav-link font-weight-bold active">All Installments</a>
                <a href="#active" class="nav-item nav-link font-weight-bold">Active</a>
                <a href="#cancelled" class="nav-item nav-link font-weight-bold">Cancelled</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="all-installments" class="tab-pane fade active show p-2">
                    <livewire:installment.installments-table />
                </div>
                <div id="active" class="tab-pane fade p-2">
                    <livewire:installment.installments-table active="true" />
                </div>
                <div id="cancelled" class="tab-pane fade p-2">
                    <livewire:installment.installments-table cancelled="true" />
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
