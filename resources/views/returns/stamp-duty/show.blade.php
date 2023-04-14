@extends('layouts.master')

@section('title', 'View Return')

@section('content')
    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:returns.return-payment :return="$return->tax_return" />
        </div>
    </div>

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Returns Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#installment-items" class="nav-item nav-link font-weight-bold active">Return Summary</a>
                <a href="#payment-structure" class="nav-item nav-link font-weight-bold ">Return Details</a>
                <a href="#penalties" class="nav-item nav-link font-weight-bold ">Penalties</a>
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="installment-items" class="tab-pane fade active show p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">Stamp Duty</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Filled By</span>
                            <p class="my-1">{{ $return->taxpayer->full_name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                            <p class="my-1">{{ $return->financialYear->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Month</span>
                            <p class="my-1">{{ $return->financialMonth->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Location</span>
                            <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Application Status</span>
                            <p class="my-1">{{ $return->application_status }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Status</span>
                            <p class="my-1">{{ $return->status }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Return Category</span>
                            <p class="my-1"><span class="badge badge-info">{{ $return->return_category }}</span></p>
                        </div>
                    </div>
                    <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                </div>
                <div id="payment-structure" class="tab-pane fade p-4">
                    <table class="table table-bordered mb-0 normal-text">
                        <thead>
                        <th style="width: 30%">Item Name</th>
                        <th style="width: 20%">Value (TZS)</th>
                        <th style="width: 10%">Rate</th>
                        <th style="width: 20%">Tax (TZS)</th>
                        </thead>
                        <tbody>
                        @foreach ($return->items as $item)
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                @if($item->config->code == 'WITHH')
                                    <td class="bg-secondary"></td>
                                @else
                                    <td>{{ number_format($item->value, 2) }}</td>
                                @endif
                                @if($item->config->rate_applicable)
                                    <td>
                                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd }}
                                    </td>
                                @else
                                    <td class="bg-secondary"></td>
                                @endif
                                @if($item->config->is_summable)
                                    <td>{{ number_format($item->vat, 2) }}</td>
                                @else
                                    <td class="bg-secondary"></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="bg-secondary">
                            <th style="width: 20%">Total</th>
                            <th style="width: 30%"></th>
                            <th style="width: 25%"></th>
                            <th style="width: 25%">{{ number_format($return->total_amount_due, 2) }}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="penalties" class="tab-pane fade p-4">
                    <table class="table table-bordered table-striped normal-text">
                        <thead>
                        <tr>
                            <th>Month</th>
                            <th>Tax Amount</th>
                            <th>Late Filing Amount</th>
                            <th>Late Payment Amount</th>
                            <th>Interest Rate</th>
                            <th>Interest Amount</th>
                            <th>Payable Amount</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(count($return->penalties))
                            @foreach ($return->penalties as $penalty)
                                <tr>
                                    <td>{{ $penalty['financial_month_name'] }}</td>
                                    <td>{{ number_format($penalty['tax_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty['late_filing'], 2) }} <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty['late_payment'], 2) }} <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                    <td>{{ number_format($penalty['rate_amount'], 2) }} <strong>{{ $return->currency}}</strong></td>
                                    <td>{{ number_format($penalty['penalty_amount'], 2)}} <strong>{{ $return->currency}}</strong></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-3">
                                    No penalties for this return.
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 d-flex justify-content-end">
                    <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-info">
                        <i class="bi bi-printer-fill mr-2"></i>
                        Print Return
                    </a>
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