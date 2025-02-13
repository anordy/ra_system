@extends('layouts.master')

@section('title', 'View Payment')

@section('content')

    @if($payment->status === \App\Enum\ReturnStatus::APPROVED)
        <div class="row m-2 pt-3">
            <div class="col-md-12">
                <livewire:taxpayer-ledger.bill-payment :payment="$payment"/>
            </div>
        </div>
    @endif

    <div class="m-2">
        <ul class="nav nav-tabs shadow-sm mb-0">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
                   aria-selected="true">Payment Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab"
                   aria-controls="approval"
                   aria-selected="false">Approval History</a>
            </li>
        </ul>

        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="card-body">
                    <div class="row m-2 pt-3">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Business Name') }}</span>
                            <p class="my-1">{{ $payment->location->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Requested By') }}</span>
                            <p class="my-1">{{ $payment->taxpayer->fullname ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Requested On') }}</span>
                            <p class="my-1">{{ $payment->created_at ? \Carbon\Carbon::create($payment->created_at)->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Control Number') }}</span>
                            <p class="my-1">{{ $payment->latestBill->control_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Total Amount') }}</span>
                            <p class="my-1">{{ $payment->currency }} {{ number_format($payment->total_amount ?? 0, 2) }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Is Partial') }}</span>
                            <p class="my-1">{{ $payment->is_partial ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Status') }}</span>
                            <p class="my-1">
                                <span class="badge badge-info">{{ ucfirst($payment->status) ?? 'N/A' }}</span>
                            </p>
                        </div>
                    </div>

                    <span class="font-weight-bold mx-4 mt-4 text-uppercase">{{ __('Payment Items') }}</span>
                    <hr>

                    @if(isset($payment->items))
                        @foreach($payment->items as $item)
                            <div class="row m-2 pt-3">
                                <div class="col-md-3 mb-3">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Debit Number') }}</span>
                                    <p class="my-1">{{ $item->ledger->debit_no ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Tax Type') }}</span>
                                    <p class="my-1">{{ $item->taxtype->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Amount') }}</span>
                                    <p class="my-1">{{ $item->currency }} {{ number_format($item->amount ?? 0, 2) }}</p>
                                </div>
                            </div>

                            @if($item->breakdown)
                                <div class="row m-2 pt-3">
                                    <div class="col-md-3 mb-3">
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th scope="col" class="w-25">Payment Name</th>
                                                <th scope="col" class="w-50">Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($item->breakdown->toArray() ?? [] as $i => $breakDown)
                                                @if($breakDown > 0)
                                                    <tr>
                                                        <td>{{ $i ? ucfirst($i) : 'N/A'  }}</td>
                                                        <td>{{ $item->currency  }} {{ number_format($breakDown ?? 0, 2) ?? 0 }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <hr>
                        @endforeach
                    @endif
                </div>

                <livewire:approval.tax-payment-partial-approval-processing
                        modelName="{{ \App\Models\TaxpayerLedger\TaxpayerLedgerPayment::class }}"
                        modelId="{{ encrypt($payment->id) }}"/>
            </div>

            <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
                <livewire:approval.approval-history-table
                        modelName="{{ \App\Models\TaxpayerLedger\TaxpayerLedgerPayment::class  }}"
                        modelId="{{ encrypt($payment->id) }}"/>
            </div>

        </div>

    </div>

@endsection
