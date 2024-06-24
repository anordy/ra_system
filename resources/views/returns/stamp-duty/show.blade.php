@extends('layouts.master')

@section('title', 'View Return')

@section('content')
    @if(!empty($return->tax_return))
        <div class="row mx-1">
            <div class="col-md-12">
                <livewire:returns.return-payment :return="$return->tax_return" />
            </div>
        </div>
    @endif

    <div class="card rounded-0">
        <div class="card-header bg-white font-weight-bold">
            Returns Details
        </div>
        <div class="card-body">
            <nav class="nav nav-tabs mt-0 border-top-0">
                <a href="#installment-items" class="nav-item nav-link font-weight-bold active">Return Summary</a>
                <a href="#payment-structure" class="nav-item nav-link font-weight-bold ">Return Details</a>
                <a href="#penalties" class="nav-item nav-link font-weight-bold ">Penalties</a>
                @if($return->withheld_certificates_summary)
                    <a href="#withheld-attachments" class="nav-item nav-link font-weight-bold ">{{ __('Withheld Summary & Attachments') }}</a>
                @endif
            </nav>
            <div class="tab-content px-2 pt-3 pb-2 border">
                <div id="installment-items" class="tab-pane fade active show p-4">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Tax Type</span>
                            <p class="my-1">Stamp Duty</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Filed By</span>
                            <p class="my-1">{{ $return->taxpayer->full_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Year</span>
                            <p class="my-1">{{ $return->financialYear->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Financial Month</span>
                            <p class="my-1">{{ $return->financialMonth->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Business Name</span>
                            <p class="my-1">{{ $return->business->name ?? 'N/A' }}</p>
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
                            @include('returns.return-payment-status', ['row' => $return])
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Return Category</span>
                            <p class="my-1"><span class="badge badge-info py-1 px-2 text-uppercase">{{ $return->return_category }}</span></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase" >{{ __('Vetting Status') }}</span>
                            <p class="my-1"><span class="badge badge-info py-1 px-2 text-uppercase">{{ $return->vetting_status }}</span></p>
                        </div>
                    </div>

                    @if(!empty($return->tax_return->latestBill))
                        <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                    @endif
                </div>
                <div id="payment-structure" class="tab-pane fade p-4">
                    <table class="table table-bordered table-responsive mb-0 normal-text">
                        <thead>
                        <th class="w-30">Item Name</th>
                        <th class="w-20">Value (TZS)</th>
                        <th class="w-10">Rate</th>
                        <th class="w-20">Tax (TZS)</th>
                        </thead>
                        <tbody>
                        @if(!empty($return->items))
                            @foreach ($return->items as $item)
                                @if($item->config->col_type === 'heading')
                                    <tr class="font-weight-bold">
                                        @foreach($item->config->headings as $heading)
                                            <th>{{ $heading['name'] }}</th>
                                        @endforeach
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $item->config->name }}</td>
                                        @if($item->config->code == 'WITHH')
                                            <td class="bg-secondary"></td>
                                        @else
                                            <td>{{ number_format($item->value, 2) }}</td>
                                        @endif
                                        @if($item->config->rate_applicable)
                                            <td>
                                                {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd .''. $item->config->currency }}
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
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                        <tfoot>
                        <tr class="bg-secondary">
                            <th class="w-20">Total</th>
                            <th class="w-30"></th>
                            <th class="w-25"></th>
                            <th class="w-25">{{ number_format($return->total_amount_due, 2) }}</th>
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
                        @if(count($return->penalties ?? []))
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
                <div id="withheld-attachments" class="tab-pane fade p-4">
                    <table class="table table-bordered table-striped normal-text">
                        <thead>
                        <tr>
                            <th width="10">{{ __('SN') }}</th>
                            <th>{{ __('Withholding Receipt No.') }}</th>
                            <th>{{ __('Withholding Receipt Date') }}</th>
                            <th>{{ __('Agent Name') }}</th>
                            <th>{{ __('Agent No') }}</th>
                            <th>{{ __('VFMS Receipt No') }}</th>
                            <th>{{ __('VFMS Receipt Date') }}</th>
                            <th>{{ __('Net Amount') }}</th>
                            <th>{{ __('Tax Withheld') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($return->withheld))
                            @foreach($return->withheld as $withheld)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $withheld->withholding_receipt_no }}</td>
                                    <td>{{ $withheld->withholding_receipt_date->toDateString() }}</td>
                                    <td>{{ $withheld->agent_name }}</td>
                                    <td>{{ $withheld->agent_no }}</td>
                                    <td>{{ $withheld->vfms_receipt_no }}</td>
                                    <td>{{ $withheld->vfms_receipt_date->toDateString() }}</td>
                                    <td>{{ number_format($withheld->net_amount) }} {{ $withheld->currency }}</td>
                                    <td>{{ number_format($withheld->tax_withheld) }} {{ $withheld->currency }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if($return->withheld_certificates_summary)
                        <a class="file-item d-inline-flex pr-3 mr-2"  target="_blank"  href="{{ route('returns.stamp-duty.withheld-certificates-summary', encrypt($return->id)) }}">
                            <i class="bi bi-file-earmark-excel px-2 font-x-large"></i>
                            <div class="ml-1 font-weight-bold">
                                Withheld Certificates Summary
                            </div>
                            <i class="bi bi-arrow-up-right-square ml-2"></i>
                        </a>
                    @endif

                    @if(!empty($return->withheldCertificates))
                        @foreach($return->withheldCertificates as $certificate)
                            <a class="file-item d-inline-flex pr-3 mr-2"  target="_blank"  href="{{ route('returns.stamp-duty.withheld-certificate', encrypt($certificate->id)) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                <div class="ml-1 font-weight-bold">
                                    Withheld Certificate {{ $loop->index + 1 }}
                                </div>
                                <i class="bi bi-arrow-up-right-square ml-2"></i>
                            </a>
                        @endforeach
                    @endif
                </div>

            </div>
            @if(!empty($return->tax_return->id))
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-info">
                            <i class="bi bi-printer-fill mr-2"></i>
                            Print Return
                        </a>
                    </div>
                </div>
            @endif

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