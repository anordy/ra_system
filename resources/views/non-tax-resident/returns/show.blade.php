@extends('layouts.master')

@section('title', 'Tax Return Details')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <livewire:non-tax-resident.returns.return-payment :return="$return"/>
        </div>
    </div>

    <ul class="nav nav-tabs shadow-sm" id="myTab" style="margin-bottom: 0;">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true">Business Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="deregister-tab" data-toggle="tab" href="#deregister" role="tab"
               aria-controls="deregister"
               aria-selected="true">Tax Return Information</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row m-2 pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">{{ __('Business Status') }}</span>
                    <p class="my-1">
                        @if($business->status === \App\Models\BusinessStatus::APPROVED)
                            <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Approved
                            </span>
                        @elseif($business->status === \App\Models\BusinessStatus::DEREGISTERED)
                            <span class="font-weight-bold text-danger">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Rejected
                            </span>
                        @else
                            <span class="font-weight-bold text-info">
                                <i class="bi bi-clock-history mr-1"></i>
                                Unknown Status
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">ZTN Number</span>
                    <p class="my-1">{{ $business->ztn_location_number }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">VRN Number</span>
                    <p class="my-1">{{ $business->vrn }}</p>
                </div>
                @if($business->category)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->category->name ?? 'N/A' }}</p>
                    </div>
                @endif
                @if($business->other_category)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Business Category</span>
                        <p class="my-1">{{ $business->other_category }}</p>
                    </div>
                @endif
                @if($business->individual_position)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Individual Position</span>
                        <p class="my-1">{{ $business->individual_position }}</p>
                    </div>
                @endif
                @if($business->individual_address)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Individual Address</span>
                        <p class="my-1">{{ $business->individual_address }}</p>
                    </div>
                @endif
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Email</span>
                    <p class="my-1">{{ $business->email }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Address</span>
                    <p class="my-1">{{ $business->business_address }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Nature of Business</span>
                    <p class="my-1">{{ $business->nature_of_business }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Country</span>
                    <p class="my-1">{{ $business->country->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Street</span>
                    <p class="my-1">{{ $business->street }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registered By</span>
                    <p class="my-1">{{ $business->taxpayer->full_name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Registration Date</span>
                    <p class="my-1">{{ $business->created_at ? \Carbon\Carbon::create($business->created_at)->format('d M, Y') : 'N/A' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Return Status</span>
                    <p class="my-1">
                        <span class="font-weight-bold text-success">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                {{ strtoupper($return->status) }}
                        </span>
                    </p>
                </div>
                @if($return->cancellation)
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Cancelled Date</span>
                        <p class="my-1">{{ $return->cancellation->created_at ? \Carbon\Carbon::create($return->cancellation->created_at)->format('d M, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Cancelled By</span>
                        <p class="my-1">{{ $return->cancellation->taxpayer->full_name ?? 'N/A'  }}</p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <span class="font-weight-bold text-uppercase">Cancellation Reason</span>
                        <p class="my-1">
                            {{ $return->cancellation->reason }}
                        </p>
                    </div>
                @endif

            </div>
        </div>
        <div class="tab-pane fade show" id="deregister" role="tabpanel" aria-labelledby="deregister-tab">
            <div class="m-4">
                <table class="table table-bordered mb-0 normal-text">
                    <thead>
                    <th class="w-30">Nature of Supplies</th>
                    <th class="w-20">Total Amount (Excluding Tax)</th>
                    <th class="w-10">Rate</th>
                    <th class="w-20">Total VAT Charged</th>
                    </thead>
                    <tbody>
                    @if(!empty($return->items))
                        @foreach ($return->items as $item)
                            <tr>
                                <td>{{ $item->config->name }}</td>
                                <td>{{ number_format($item->value, 2) }}</td>
                                @if($item->config->rate_applicable)
                                    <td>
                                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate . '%' : $item->config->rate_usd }}
                                    </td>
                                @else
                                    <td class="bg-secondary"></td>
                                @endif
                                <td>{{ number_format($item->vat, 2) }}</td>
                                {{--                                    <td class="bg-secondary"></td>--}}
                            </tr>
                        @endforeach
                    @else
                        <span>{{ __('No Return Items Found')  }}</span>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr class="bg-secondary">
                        <th class="w-20">{{ __('Total') }}</th>
                        <th class="w-30"></th>
                        <th class="w-25"></th>
                        <th class="w-25">{{ number_format($return->total_amount_due) }}</th>
                    </tr>
                    </tfoot>
                </table>
                @if($return->attachments)
                    <table class="table table-bordered table-striped normal-text mt-4">
                        <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Service Description</th>
                            <th>Amount (USD)</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($return->attachments as $attachment)
                            <tr>
                                <td>{{ $attachment->customer_name }}</td>
                                <td>{{ $attachment->service_description }}
                                <td>{{ number_format($attachment->paid_amount ?? 0, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="2" class="text-right">Total Amount:</th>
                            <th>
                                {{ number_format($return->attachments->sum('paid_amount'), 2) }}
                            </th>
                        </tr>
                        </tfoot>
                    </table>
                @endif
                <table class="table table-bordered table-striped normal-text mt-4">
                    <thead>
                    <tr>
                        <th>{{ __('Month') }}</th>
                        <th>{{ __('Tax Amount') }}</th>
                        <th>{{ __('Late Filing Amount') }}</th>
                        <th>{{ __('Late Payment Amount') }}</th>
                        <th>{{ __('Interest Rate') }}</th>
                        <th>{{ __('Interest Amount') }}</th>
                        <th>{{ __('Payable Amount') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if(count($return->penalties))
                        @foreach ($return->penalties as $penalty)
                            <tr>
                                <td>{{ $penalty['financial_month_name'] }}</td>
                                <td>{{ number_format($penalty['tax_amount'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['late_filing'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['late_payment'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['rate_percentage'], 4) }}</td>
                                <td>{{ number_format($penalty['rate_amount'], 2) }}
                                    <strong>{{ $return->currency}}</strong></td>
                                <td>{{ number_format($penalty['penalty_amount'], 2)}}
                                    <strong>{{ $return->currency}}</strong></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-3">
                                {{ __('No penalties for this return') }}.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection