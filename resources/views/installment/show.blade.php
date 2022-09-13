@extends('layouts.master')

@section('title', 'Installment Details')

@section('content')
    <div class="container-fluid">
        @if($installment->getNextPaymentDate())
            <livewire:installment.installment-payment :installment="$installment" />
        @endif
    </div>
    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Installment Total Amount</span>
                        <p class="my-1">{{ $installment->currency }}. {{ number_format($installment->amount, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">No. of Installments </span>
                    <p class="my-1">{{ $installment->installment_count }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount per Installment</span>
                    <p class="my-1">{{ $installment->currency }}. {{ number_format($installment->amount/$installment->installment_count, 2) }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Start Date</span>
                    <p class="my-1">{{ $installment->installment_from->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Due Date</span>
                    <p class="my-1">{{ $installment->installment_to->toDayDateTimeString() }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Type</span>
                    <p class="my-1">{{ $installment->taxType->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1 text-capitalize">{{ $installment->status }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Installment Items
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#all-installments" class="nav-item nav-link font-weight-bold active">Installments Phases</a>
                <a href="#active" class="nav-item nav-link font-weight-bold">Payments</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="all-installments" class="tab-pane fade active show p-2">
                    <div class="table table-bordered table-sm table-striped mb-0">
                        <table class="w-100">
                            <thead>
                            <tr>
                                <th>Installment Phase</th>
                                <th>Start Date</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @for($i = 1; $i <= $installment->installment_count; $i++)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $installment->installment_from->addMonths($i - 1)->toDayDateTimeString() }}</td>
                                    <td>{{ $installment->installment_from->addMonths($i)->toDayDateTimeString() }}</td>
                                </tr>
                            @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="active" class="tab-pane fade p-2">
                    <livewire:installment.installment-items-table :installment="$installment" />
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
