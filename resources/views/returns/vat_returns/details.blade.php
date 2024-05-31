<div class="card">
    <div class="card-body">
        <h6 class="text-uppercase mt-2 ml-2">Filed Return Details For {{ $return->taxtype->name ?? 'N/A' }}</h6>
        <hr>
        <div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="academic-tab" data-toggle="tab" href="#academic" role="tab"
                       aria-controls="profile" aria-selected="false">Return Details</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="prof-tab" data-toggle="tab" href="#prof" role="tab"
                       aria-controls="contact"
                       aria-selected="false">Supplier & Cash Sales</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="hotel-tab" data-toggle="tab" href="#hotel" role="tab"
                       aria-controls="contact"
                       aria-selected="false">Hotel</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="zero-tab" data-toggle="tab" href="#zero" role="tab"
                       aria-controls="contact"
                       aria-selected="false">Zero Rated</a>
                </li>


                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="training-tab" data-toggle="tab" href="#training" role="tab"
                       aria-controls="contact" aria-selected="false">Penalties</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="payment-summary-tab" data-toggle="tab" href="#payment-summary" role="tab"
                       aria-controls="payment-summary" aria-selected="false">Payment Summary</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="withheld-tab" data-toggle="tab" href="#withheld"
                       role="tab"
                       aria-controls="withheld" aria-selected="false">Withheld</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="special-relief-tab" data-toggle="tab" href="#special-relief" role="tab"
                       aria-controls="special-relief"
                       aria-selected="false">Special Relief</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="exempt-supplies-tab" data-toggle="tab" href="#exempt-supplies" role="tab"
                       aria-controls="exempt-supplies"
                       aria-selected="false">Exempt Supplies</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">

                <div class="tab-pane p-2 show active" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">

                                <div class="pb-2">
                                    <label>{{ __('Exemption Method Used') }}</label>
                                    <input readonly class="form-control" type="text"
                                           value="{{ $return->method_used ?? 'No Method Used' }}">
                                </div>

                            </div>

                            <table class="table table-bordered table-responsive">
                                <thead>
                                <th>Item Name</th>
                                <th>Value</th>
                                <th>Rate</th>
                                <th class="text-right">VAT</th>
                                </thead>
                                <tbody>
                                @if(!empty($return->items))
                                    @foreach ($return->items as $item)
                                        @if($item->config->code == 'ITH')
                                            @if($return->business->business_type =='hotel')
                                                <tr>
                                                    <td>{{ $item->config->name }}</td>
                                                    <td class="text-right">{{ number_format($item->value, 2) }} <strong>(No.
                                                            of bed nights)</strong></td>
                                                    <td>
                                                        {{ $item->config->rate_type === 'percentage' ? $item->config->rate : getHotelStarByBusinessId($return->business->id)->infrastructure_charged }}
                                                        @if($item->config->rate_type =='percentage')
                                                            %
                                                        @else
                                                            @if ($item->config->currency == 'both')
                                                                <strong>TZS</strong> <br>
                                                                <strong>USD</strong>
                                                            @elseif ($item->config->currency == 'TZS')
                                                                <strong>TZS</strong>
                                                            @elseif ($item->config->currency == 'USD')
                                                                <strong>USD</strong>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                                                        <strong>{{$return->currency}}</strong></td>
                                                </tr>
                                            @endif
                                        @elseif($item->config->code == 'ITE')
                                            @if($return->business->business_type =='electricity')
                                                <tr>
                                                    <td>{{ $item->config->name }}</td>
                                                    <td class="text-right">{{ number_format($item->value, 2) }}
                                                        <strong>(Electricity Units)</strong></td>
                                                    <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate }}
                                                        @if($item->config->rate_type =='percentage')
                                                            %
                                                        @else
                                                            {{$return->currency}}
                                                        @endif
                                                    </td>
                                                    <td class="text-right">{{ number_format($return->infrastructure_tax,2) }}
                                                        <strong>{{$return->currency}}</strong></td>
                                                </tr>
                                            @endif
                                        @elseif($item->config->code != 'TIT' && $item->config->code != 'TITM1')
                                            <tr>
                                                <td>{{ $item->config->name }}</td>
                                                <td class="text-right">{{ number_format($item->value, 2) }}
                                                    <strong>  {{ $return->currency}}</strong></td>
                                                <td>{{ $item->config->rate_type === 'percentage' ? $item->config->rate : $item->config->rate_usd }}
                                                    @if($item->config->rate_type =='percentage')
                                                        %
                                                    @endif
                                                </td>
                                                <td class="text-right">{{ number_format($item->vat,2) }}
                                                    <strong>{{$return->currency}}</strong></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif

                                </tbody>

                                <tbody>

                                <tr>
                                    <td>Total Output Tax</td>
                                    <td colspan="2" class="table-active"></td>
                                    <td class="text-right">{{number_format($return->total_output_tax,2, '.',',')}}
                                        <strong>{{$return->currency}}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Total Input Tax</td>
                                    <td colspan="2" class="table-active"></td>
                                    <td class="text-right">{{number_format($return->total_input_tax,2, '.',',')}}
                                        <strong>{{$return->currency}}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                                            Vat To Claim
                                        @else
                                            Vat Payable
                                        @endif
                                    </th>
                                    <td colspan="2" class="table-active"></td>
                                    <th class="text-right">
                                        @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                                            ({{number_format(abs($return->total_output_tax - $return->total_input_tax), 2, '.',',')}}
                                            )
                                        @else
                                            {{number_format($return->total_vat_payable, 2, '.',',')}}
                                        @endif
                                        <strong>{{$return->currency}}</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <td>Vat Withheld</td>
                                    <td colspan="2" class="table-active"></td>
                                    <td class="text-right">{{number_format($return->vat_withheld,2, '.',',')}}
                                        <strong>{{$return->currency}}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Vat Credit Brought Forward</td>
                                    <td colspan="2" class="table-active"></td>
                                    <td class="text-right">{{number_format($return->vat_credit_brought_forward,2, '.',',' )}}
                                        <strong>{{$return->currency}}</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                                            Net Vat To Claim
                                        @else
                                            Net Vat Payable
                                        @endif
                                    </th>
                                    <td colspan="2" class="table-active"></td>
                                    <th class="text-right">
                                        @if($return->claim_status == \App\Enum\TaxClaimStatus::CLAIM)
                                            ({{number_format($return->total_amount_due, 2, '.',',')}})
                                        @else
                                            {{number_format($return->total_amount_due, 2, '.',',')}}
                                        @endif
                                        <strong>{{$return->currency}}</strong>
                                    </th>
                                </tr>

                                <tr>
                                    @if($return->business_type != 'other')
                                        <th>IInfrastructure Tax ({{$return->business_type}})</th>
                                        <td colspan="2" class="table-active"></td>
                                        <th class="text-right">{{number_format($return->infrastructure_tax,2, '.',',' )}}
                                            <strong>{{$return->currency}}</strong>
                                        </th>
                                    @endif
                                </tr>

                                <tr>
                                    <th>Penalty</th>
                                    <td colspan="2" class="table-active"></td>
                                    <th class="text-right">{{number_format($return->penalty,2, '.',',' )}}
                                        <strong>{{$return->currency}}</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <th>Interest</th>
                                    <td colspan="2" class="table-active"></td>
                                    <th class="text-right">{{number_format($return->interest,2, '.',',' )}}
                                        <strong>{{$return->currency}}</strong>
                                    </th>
                                </tr>

                                <tr>
                                    <th>Grant Amount Payable</th>
                                    <td colspan="2" class="table-active"></td>
                                    <th class="text-right">{{number_format($return->total_amount_due, 2, '.',',')}}
                                        <strong>{{$return->currency}}</strong>
                                    </th>
                                </tr>
                                @if(count($return->penalties))
                                    <tr>
                                        <th>Grant Amount Payable With Penalties</th>
                                        <td colspan="2" class="table-active"></td>
                                        <th class="text-right">{{number_format($return->total_amount_due_with_penalties, 2, '.',',')}}
                                            <strong>{{$return->currency}}</strong>
                                        </th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>

                        </div>

                        @if(count($return->vatWithheld) > 0)
                            <label>Withheld Attachment</label>
                            <div class="row">
                                @foreach($return->vatWithheld as $file)
                                    <div class="col-md-3">
                                        <a class="file-item" target="_blank"
                                           href="{{ route('returns.vat-return.withheld-file', [encrypt($file->id), 'withheld']) }}">
                                            <i class="bi bi-file-earmark-pdf-fill px-2 font-x-large"></i>
                                            <div class="ml-1 font-weight-bold">
                                                View Attachment
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane p-2" id="prof" role="tabpanel" aria-labelledby="prof-tab">
                    <div class="card">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">Suppliers Details for 15 percent</div>

                                <div class="card-body">
                                    <table class="table table-bordered table-sm normal-text">
                                        <thead>
                                        <tr>
                                            <th>No:</th>
                                            <th>{{ __('Supplier ZTN /TIN Number') }}</th>
                                            <th>{{ __('VFMS Receipt Number') }}</th>
                                            <th>{{ __('VFMS Receipt Date') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('Vat') }}</th>
                                            <th>{{ __('Supply Type') }}</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if (count($return->suppliers ?? []))
                                            @foreach ($return->suppliers as $index=> $details)
                                                {{--                                                        {{ dd($details->supplierDetailsItems) }}--}}
                                                @if($details['supply_type'] == 'fifteen_percent')
                                                    <tr>
                                                        <th>{{$index + 1}}</th>
                                                        <td>{{ $details['supplier_zin_number'] }} </td>

                                                        <td>{{ $details['tax_invoice_number'] }}</td>

                                                        <td>{{ date('D, Y-m-d', strtotime($details['date_of_tax_invoice'])) }} </td>

                                                        <td class="text-right">{{ number_format($details['value'],2,'.',',') }} </td>

                                                        <td class="text-right">{{ number_format($details['vat'],2,'.',',')  }} </td>

                                                        <td class="text-right">
                                                            @if($details['supply_type'] == 'fifteen_percent')
                                                                15 %
                                                            @else
                                                                18 %
                                                            @endif
                                                        </td>

                                                    </tr>
                                                    <tr>
                                                        <td colspan="7" class="p-2">
                                                            <label class="font-weight-bold">Receipt Items</label>

                                                            <table class="table table-bordered table-sm normal-text">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-right">No:</th>
                                                                    <th>{{ __('Item Name') }}</th>
                                                                    <th>{{ __('Price') }}</th>
                                                                    <th>{{ __('Quantity') }}</th>
                                                                    <th>{{ __('Total Amount') }}</th>
                                                                    <th>{{ __('Taxable') }}</th>
                                                                    <th>{{ __('Used') }}</th>

                                                                </tr>
                                                                </thead>

                                                                <tbody>
                                                                @if (count($details->supplierDetailsItems ?? []))
                                                                    @foreach ($details->supplierDetailsItems as $key => $supplierDetail)
                                                                        <tr>
                                                                            <th class="text-right">{{ romanNumeralCount($index + 1) }}</th>
                                                                            <td>{{ $supplierDetail['name'] }} </td>

                                                                            <td class="text-right">{{ number_format($supplierDetail['price'],2,'.',',') }} </td>

                                                                            <td>{{ $supplierDetail['quantity'] }}</td>

                                                                            <td class="text-right">{{ number_format($supplierDetail['total_amount'],2,'.',',')  }} </td>

                                                                            <td class="font-weight-bold {{ $supplierDetail['is_taxable'] ? 'text-success' : 'text-muted' }}">{{ $supplierDetail['is_taxable'] ? 'Taxable' : 'Non Taxable' ?? 'N/A' }}</td>

                                                                            <td class="font-weight-bold {{ $supplierDetail['used'] ? 'text-success' : 'text-danger' }}">{{ $supplierDetail['used'] ? 'True' : 'False' ?? 'N/A' }}</td>

                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    <tr>
                                                                        <td colspan="7" class="text-center py-3">
                                                                            {{ __('No details for supplier for this return month') }}.
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    {{ __('No details for supplier for this return month') }}.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">Suppliers Details For 18 percent</div>

                                <div class="card-body">
                                    <table class="table table-bordered table-sm normal-text">
                                        <thead>
                                        <tr>
                                            <th>No:</th>
                                            <th>{{ __('Supplier ZTN /TIN Number') }}</th>
                                            <th>{{ __('VFD Receipt Number') }}</th>
                                            <th>{{ __('VFD Receipt Date') }}</th>
                                            <th>{{ __('Customs Entry Number') }}</th>
                                            <th>{{ __('Value') }}</th>
                                            <th>{{ __('Vat') }}</th>
                                            <th>{{ __('Supply Type') }}</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if (count($return->suppliers ?? []))
                                            @foreach ($return->suppliers as $index=> $details)
                                                @if($details['supply_type'] != 'fifteen_percent')
                                                    <tr>
                                                        <th>{{$index + 1}}</th>
                                                        <td>{{ $details['supplier_zin_number'] }} </td>

                                                        <td>{{ $details['tax_invoice_number'] }}</td>

                                                        <td>{{ date('D, Y-m-d', strtotime($details['date_of_tax_invoice'])) }} </td>

                                                        <td>{{ $details['release_number'] }}</td>

                                                        <td class="text-right">{{ number_format($details['value'],2,'.',',') }} </td>

                                                        <td class="text-right">{{ number_format($details['vat'],2,'.',',')  }} </td>

                                                        <td class="text-right">
                                                            @if($details['supply_type'] == 'fifteen_percent')
                                                                15 %
                                                            @else
                                                                18 %
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    {{ __('No details for supplier for this return month') }}.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">Import Purchases Details (IM4)</div>
                                @if(isset($return->importPurchases) && count($return->importPurchases ?? []) > 0)
                                    <div class="card-body">
                                        <table class="table table-sm px-2">
                                            <thead>
                                            <th>No</th>
                                            <th>Supplier TIN</th>
                                            <th>VRN</th>
                                            <th>Tansad Number</th>
                                            <th>Tansad Date</th>
                                            <th>Value Excl. Tax</th>
                                            <th>Tax Amount</th>
                                            <th>Release Date</th>
                                            </thead>
                                            <tbody>
                                            @foreach($return->importPurchases as $itemKey => $item)
                                                <tr>
                                                    <td class="px-2">{{ $itemKey + 1 }}</td>
                                                    <td class="px-2">{{ $item->supplier_tin_number ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ $item->vat_registration_number ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ $item->tansad_number ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ $item->tansad_date ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ number_format($item->value_excluding_tax ?? 0, 2) }}</td>
                                                    <td class="px-2">{{ number_format($item->tax_amount ?? 0, 2) }}</td>
                                                    <td class="px-2">{{ $item->release_date ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="card">
                                <div class="card-header">Standard Purchases Details (IM9)</div>
                                @if(isset($return->standardPurchases) && count($return->standardPurchases ?? []) > 0)
                                    <div class="card-body">
                                        <table class="table table-sm px-2">
                                            <thead>
                                            <th>No</th>
                                            <th>Tansad Number</th>
                                            <th>EFD Number</th>
                                            <th>Item Name</th>
                                            <th>Exclusive Tax Amount</th>
                                            </thead>
                                            <tbody>
                                            @foreach($return->standardPurchases as $itemKey => $item)
                                                <tr>
                                                    <td class="px-2">{{ $itemKey + 1 }}</td>
                                                    <td class="px-2">{{ $item->tansad_number ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ $item->efd_number ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ $item->item_name ?? 'N/A' }}</td>
                                                    <td class="px-2">{{ number_format($item->exclusive_tax_amount ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                            <div class="card">
                                <div class="card-header">Cash Sales</div>

                                <div class="card-body">
                                    <table class="table table-bordered table-sm normal-text">
                                        <thead>
                                        <tr>
                                            <th>No:</th>
                                            <th>{{ __('Documents') }}</th>
                                            <th>{{ __('From Number') }}</th>
                                            <th>{{ __('To Number') }}</th>
                                            <th>{{ __('Remarks') }}</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        @if (count($return->cashSales ?? []))
                                            @foreach ($return->cashSales as $index=> $details)
                                                <tr>
                                                    <th>{{$index + 1}}</th>
                                                    <td>{{ $details['document'] }}</td>

                                                    <td>{{ $details['from_number'] }}</td>

                                                    <td>{{ $details['to_number'] }}</td>

                                                    <td>{{ $details['remarks'] }}</td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    {{ __('No details for cash sales for this return month') }}.
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th class="text-center">{{ __('NO. OF DAYS') }}</th>
                                    <th class="text-center" colspan="3">{{ __('NO. OF PAX') }}</th>
                                    <th class="text-center" colspan="3">{{ __('REVENUE') }}</th>
                                    <th class="text-center">{{ __('TOTAL') }}</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th class="text-center">{{ __('R') }}</th>
                                    <th class="text-center">{{ __('NR') }}</th>
                                    <th class="text-center">{{ __('TOTAL PAX') }}</th>
                                    <th class="text-center">{{ __('ACCOMMODATION') }}</th>
                                    <th class="text-center">{{ __('RESTAURANT') }}</th>
                                    <th class="text-center">{{ __('OTHERS') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (count($return->hotelDetails ?? []))
                                    @foreach ($return->hotelDetails as $index=> $details)
                                        <tr>
                                            <th class="text-center">{{ $details['no_of_days'] }}</th>
                                            <td class="text-center">{{ $details['no_of_pax_for_r'] }}

                                            <td class="text-center">{{ $details['no_of_pax_for_nr'] }}

                                            <td class="text-center">{{ $details['total_no_of_pax'] }}

                                            <td class="text-center">{{ $details['total_room_revenue'] }}

                                            <td class="text-center">{{ $details['revenue_for_food'] + $details['revenue_for_beverage'] }}

                                            <td class="text-center">{{ $details['other_revenue'] }}</td>
                                            </td>
                                            <td class="text-center">{{ $details['total_revenue'] }}

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            {{ __('No details for hotel for this return month') }}.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="zero" role="tabpanel" aria-labelledby="zero-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th class="text-center">No:</th>
                                    <th class="text-center">{{ __('Receipt Number') }}</th>
                                    <th class="text-center">{{ __('Receipt Date') }}</th>
                                    <th class="text-center">{{ __('Amount') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (count($return->zeroRatedDetails ?? []))
                                    @foreach ($return->zeroRatedDetails as $index=> $details)
                                        <tr>
                                            <th class="text-center">{{$index + 1}}</th>
                                            <td class="text-center">{{ $details['receipt_number'] }}

                                            <td class="text-center">{{ date('D, Y-m-d', strtotime($details['receipt_date'])) }}

                                            <td class="text-center">{{ number_format($details['amount'],2,'.',',')  }} </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            {{ __('No details for zero rated supply for this return month') }}.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="training" role="tabpanel" aria-labelledby="training-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th>NO:</th>
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
                                @if (count($return->penalties ?? []))
                                    @foreach ($return->penalties as $index=> $penalty)
                                        <tr>
                                            <th>{{$index + 1}}</th>
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
                                            No penalties for this return.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="payment-summary" role="tabpanel" aria-labelledby="payment-summary-tab">
                    <x-bill-structure :bill="$return->tax_return->latestBill" :withCard="false"/>
                </div>

                <div class="tab-pane p-2" id="withheld" role="tabpanel" aria-labelledby="withheld-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">{{ __('Withholding Receipt No') }}</th>
                                    <th class="text-center">{{ __('Withholding Receipt Date') }}</th>
                                    <th class="text-center">{{ __('VFMS Receipt No') }}</th>
                                    <th class="text-center">{{ __('VFMS Receipt Date') }}</th>
                                    <th class="text-center">{{ __('Agent Name') }}</th>
                                    <th class="text-center">{{ __('Agent No') }}</th>
                                    <th class="text-center">{{ __('Net Amount') }}</th>
                                    <th class="text-center">{{ __('Tax Withheld') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (count($return->withheldDetails ?? []))
                                    @foreach ($return->withheldDetails as $index=> $details)
                                        <tr>
                                            <th class="text-center">{{$index + 1}}</th>
                                            <td class="text-center">{{ $details['withholding_receipt_no'] }} </td>

                                            <td class="text-center">{{ $details['withholding_receipt_date'] }}</td>

                                            <td class="text-center">{{ $details['vfms_receipt_no'] }}</td>

                                            <td class="text-center">{{ $details['vfms_receipt_date'] }}</td>

                                            <td class="text-center">{{ ucwords($details['agent_name']) }}</td>

                                            <td class="text-center">{{ $details['agent_no'] }}</td>
                                            <td class="text-center">{{ $details['net_amount'] }} </td>
                                            <td class="text-center">{{ $details['tax_withheld'] }} </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            {{ __('No details for withhold for this return month') }}.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="special-relief" role="tabpanel" aria-labelledby="special-relief-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th class="text-center">No:</th>
                                    <th class="text-center">{{ __('Receipt Number') }}</th>
                                    <th class="text-center">{{ __('Receipt Date') }}</th>
                                    <th class="text-center">{{ __('Amount') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (count($return->specialRelief ?? []))
                                    @foreach ($return->specialRelief as $index=> $details)
                                        <tr>
                                            <th class="text-center">{{$index + 1}}</th>
                                            <td class="text-center">{{ $details['receipt_number'] }}

                                            <td class="text-center">{{ date('D, Y-m-d', strtotime($details['receipt_date'])) }}

                                            <td class="text-center">{{ number_format($details['amount'],2,'.',',')  }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            {{ __('No details for zero rated supply for this return month') }}.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane p-2" id="exempt-supplies" role="tabpanel" aria-labelledby="exempt-supplies-tab">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-sm normal-text">
                                <thead>
                                <tr>
                                    <th class="text-center">No:</th>
                                    <th class="text-center">{{ __('Receipt Number') }}</th>
                                    <th class="text-center">{{ __('Receipt Date') }}</th>
                                    <th class="text-center">{{ __('Amount') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if (count($return->exemptSupplies ?? []))
                                    @foreach ($return->exemptSupplies as $index=> $details)
                                        <tr>
                                            <th class="text-center">{{$index + 1}}</th>
                                            <td class="text-center">{{ $details['receipt_number'] }}

                                            <td class="text-center">{{ date('D, Y-m-d', strtotime($details['receipt_date'])) }}

                                            <td class="text-center">{{ number_format($details['amount'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            {{ __('No details for zero rated supply for this return month') }}.
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
