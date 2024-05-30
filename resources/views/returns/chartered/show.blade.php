@extends('layouts.master')

@section('title', __('View Chartered Returns'))

@section('content')
    <div class="d-flex justify-content-end pb-2">
        <a href="{{ route('returns.print', encrypt($return->tax_return->id)) }}" target="_blank" class="btn btn-outline-info px-3 mb-2 mr-2">
            <i class="bi bi-printer-fill mr-2"></i>
            {{ __('Print Return') }}
        </a>
    </div>


    <div class="mx-3">
        <livewire:returns.return-payment :return="$return->tax_return" />
    </div>

    <div class="card">
        <div class="card-header bg-white font-weight-bold text-uppercase">
            {{ $return->taxtype->name ?? 'N/A' }} {{ __('Returns Details for') }}
            {{ $return->financialMonth->name ?? 'N/A' }},
            {{ $return->financialMonth->year->code ?? 'N/A' }}
        </div>
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#biz" role="tab"
                           aria-controls="home" aria-selected="true">{{ __('Business Details') }}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                           aria-controls="profile" aria-selected="false">{{ __('Return Items') }}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="penalty-tab" data-toggle="tab" href="#penalty" role="tab"
                           aria-controls="penalty" aria-selected="false">{{ __('Penalties') }}</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane p-2 show active" id="biz" role="tabpanel" aria-labelledby="biz-tab">
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Tax Type') }}</span>
                                <p class="my-1">{{ $return->taxtype->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Filed By') }}</span>
                                <p class="my-1">{{ $return->taxpayer->full_name ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Financial Year') }}</span>
                                <p class="my-1">{{ $return->financialYear->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Business Name') }}</span>
                                <p class="my-1">{{ $return->business->name ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Business Location') }}</span>
                                <p class="my-1">{{ $return->branch->name ?? 'Head Quarter' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Application Status') }}</span>
                                <p class="my-1 text-capitalize">
                                    @include('returns.includes.return-application-status', ['status' => $return->application_status])
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Payment Status') }}</span>
                                <p class="my-1 text-capitalize">
                                    @include('returns.includes.return-payment-status', ['row' => $return])
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Return Category') }}</span>
                                <p class="my-1"><span class="badge badge-info">{{ $return->return_category }}</span></p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Vetting Status') }}</span>
                                <p class="my-1"><span class="badge badge-info">{{ $return->vetting_status }}</span></p>
                            </div>
                        </div>


                        @if ($return->tax_return->latestBill ?? null)
                            <p class="text-uppercase mb-2 font-weight-bold">{{ __('Bill Details') }}</p>

                            <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false" />
                        @endif

                    </div>

                    <div class="tab-pane p-2" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                        <div class="row mt-3">
                            @if ($return->items ?? null)
                                <div class="col-md-12">
                                    <p class="text-uppercase font-weight-bold">{{ __('Return Items') }} (TZS)</p>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped normal-text">
                                        <thead>
                                        <th class="w-30">{{ __('Item Name') }}</th>
                                        <th class="w-20">{{ __('Value') }}</th>
                                        <th class="w-10">{{ __('Rate') }}</th>
                                        <th class="w-20">{{ __('Tax') }}</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($return->items as $item)
                                            <tr>
                                                <td>{{ $item->config->name }}</td>
                                                <td>{{ number_format($item->value, 2) }}</td>
                                                <td>
                                                    @if ($item->config->rate_type == 'fixed')
                                                        @if ($item->config->currency == 'both')
                                                            {{ $item->config->rate }} TZS <br>
                                                            {{ $item->config->rate_usd }} USD
                                                        @elseif ($item->config->currency == 'TZS')
                                                            {{ $item->config->rate }} TZS
                                                        @elseif ($item->config->currency == 'USD')
                                                            {{ $item->config->rate_usd }} USD
                                                        @endif
                                                    @elseif ($item->config->rate_type == 'percentage')
                                                        {{ $item->config->rate }} %
                                                    @endif
                                                    {{-- {{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }} --}}
                                                </td>
                                                <td>{{ number_format($item->vat, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            @endif

                        </div>
                    </div>

                    <div class="tab-pane p-2" id="penalty" role="tabpanel" aria-labelledby="penalty-tab">

                        @if ($return)
                            <div class="col-md-12">
                                <h6 class="text-uppercase mt-2 ml-2">{{ __('Penalties') }} (Tzs)</h6>
                                <hr>
                                <table class="table table-bordered table-sm normal-text">
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
                                    @if (count($return->penalties ?? []) > 0)
                                        @foreach ($return->penalties as $penalty)
                                            <tr>
                                                <td>{{ $penalty['financial_month_name'] }}</td>
                                                <td>{{ number_format($penalty['tax_amount'], 2) }}
                                                    <strong>{{ $return->currency }}</strong></td>
                                                <td>{{ number_format($penalty['late_filing'], 2) }}
                                                    <strong>{{ $return->currency }}</strong></td>
                                                <td>{{ number_format($penalty['late_payment'], 2) }}
                                                    <strong>{{ $return->currency }}</strong></td>
                                                <td>{{ number_format($penalty['rate_percentage'], 4) }}
                                                </td>
                                                <td>{{ number_format($penalty['rate_amount'], 2) }}
                                                    <strong>{{ $return->currency }}</strong></td>
                                                <td>{{ number_format($penalty['penalty_amount'], 2) }}
                                                    <strong>{{ $return->currency }}</strong></td>
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
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
