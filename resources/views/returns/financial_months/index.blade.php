@extends('layouts.master')

@section('title', 'Financial Months')

@section('content')
    <div class="card">
        <div class="card-header font-weight-bold text-uppercase bg-white">
            Financial Months Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#tab1" class="nav-item nav-link font-weight-bold active">Normal Financial Month</a>
                <a href="#tab2" class="nav-item nav-link font-weight-bold">Seven Days Financial Month</a>
            </nav>
            <div class="tab-content px-2 border border-top-0 pt-3 pb-2">
                <div id="tab1" class="tab-pane fade active show">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-uppercase font-weight-bold">Financial Months Configuration</div>
                            @can('setting-financial-month-add')
                                <div class="card-tools">
                                    <button class="btn btn-info btn-sm"
                                            onclick="Livewire.emit('showModal', 'returns.financial-months.add-month-modal')"><i
                                                class="bi bi-plus-circle-fill"></i> Add
                                    </button>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            @livewire('returns.financial-months.financial-months-table')
                        </div>
                    </div>
                </div>
                <div id="tab2" class="tab-pane fade">
                    <div class="card">
                        <div class="card-header">
                            <div class="text-uppercase font-weight-bold">Seven Days Financial Months Configuration</div>
                            @can('setting-financial-month-add')
                                <div class="card-tools">
                                    <button class="btn btn-info btn-sm"
                                            onclick="Livewire.emit('showModal', 'returns.seven-days-financial-months.add-month-modal')"><i
                                                class="bi bi-plus-circle-fill"></i> Add
                                    </button>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            @livewire('returns.seven-days-financial-months.seven-days-financial-months-table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $(".nav-tabs a").click(function () {
                $(this).tab('show');
            });
        });
    </script>
@endsection