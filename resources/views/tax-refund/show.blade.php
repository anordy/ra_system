@extends('layouts.master')

@section('title', 'Show Tax Refunds')

@section('content')

    <div class="row mx-1">
        <div class="col-md-12">
            <livewire:tax-refund.tax-refund-payment :taxRefund="$taxRefund" />
        </div>
    </div>

    <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Tax Refund Information</a>
        </li>

    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-uppercase">Tax Refund Information</h5>
                </div>
                <div class="card-body">
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Status</span>
                            <p class="my-1">
                                @if($taxRefund->payment_status == \App\Models\Returns\ReturnStatus::COMPLETE)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi bi-check-circle-fill mr-1"></i>
                                                {{ __('PAID') }}
                                            </span>
                                @elseif($taxRefund->payment_status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY || $taxRefund->payment_status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: rgba(220,206,53,0.35); color: #cfc61c;; font-size: 85%">
                                                Partially Paid
                                            </span>

                                @elseif($taxRefund->payment_status == \App\Models\Returns\ReturnStatus::SUBMITTED)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                {{ __('Submitted') }}
                                            </span>
                                @elseif($taxRefund->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATING)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                {{ __('Control Number Generating') }}
                                            </span>
                                @elseif($taxRefund->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATED)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                {{ __('Control Number Generated') }}
                                            </span>
                                @elseif($taxRefund->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi-x-circle-fill mr-1"></i>
                                                {{ __('Control Number Generation Failed') }}
                                            </span>
                                @else
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #86efac; color: #166534; font-size: 85%">
                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                {{ ucwords(str_replace('-', ' ', $taxRefund->payment_status)) }}
                                            </span>
                                @endif

                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Importer Name</span>
                            <p class="my-1">{{ $taxRefund->importer_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Phone Number</span>
                            <p class="my-1">{{ $taxRefund->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">ZTN Number</span>
                            <p class="my-1">{{ $taxRefund->ztn_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Total Exclusive Amount</span>
                            <p class="my-1">{{ $taxRefund->total_exclusive_tax_amount ? number_format($taxRefund->total_exclusive_tax_amount, 2) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Rate</span>
                            <p class="my-1">{{ $taxRefund->rate ? number_format($taxRefund->rate, 2) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Total Payable Amount</span>
                            <p class="my-1">{{ $taxRefund->total_payable_amount ? number_format($taxRefund->total_payable_amount, 2) : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Receipt Number</span>
                            <p class="my-1">{{ $taxRefund->receipt_number ?? 'N/A' }}</p>
                        </div>

                    </div>

                    <div class="row m-2 pt-3">
                        @if(count($taxRefund->items))
                            <label>Refund Items</label>
                            <table class="table table-bordered table-sm table-striped normal-text mb-0">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Item Name</th>
                                    <th>Tansad Number</th>
                                    <th>Efd Number</th>
                                    <th>Excl Tax Amount</th>
                                    <th>Rate</th>
                                    <th>Payable Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($taxRefund->items))
                                    @foreach ($taxRefund->items as $i => $item)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $item->item_name ?? 'N/A' }}</td>
                                            <td>{{ $item->tansad_number ?? 'N/A' }}</td>
                                            <td>{{ $item->efd_number ?? 'N/A' }}</td>
                                            <td>{{ $item->exclusive_tax_amount ? number_format($item->exclusive_tax_amount, 2) : 'N/A' }}</td>
                                            <td>{{ $item->rate ? number_format($item->rate, 2) : 'N/A' }}</td>
                                            <td>{{ $item->exclusive_tax_amount * $item->rate }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            No Refund items available.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>

            </div>


        </div>


    </div>
@endsection