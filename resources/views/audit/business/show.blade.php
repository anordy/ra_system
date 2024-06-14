@extends("layouts.master")

@section("title", "Tax Audit Business Preview")

@section("content")

    <div class="card p-2">

        <div class="card mt-2">
            <div class="card-header text-uppercase font-weight-bold bg-white">
                <span>Business Details:</span>
                <div class="container d-flex justify-content-between">
                    <div class="card-tools">
                        <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'audit.business-audit-add-modal', {{ json_encode(["business_id" => $location->business->id, "location_ids" => $location->id]) }})">
                            Add To Audit
                            <i class="fa fa-plus-circle"></i>
                        </button>

                        <button class="btn btn-info btn-sm"
                            onclick="Livewire.emit('showModal', 'investigation.business-investigation-add-modal')">
                            Foward to Investigation
                            <i class="fa fa-plus-circle"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
        <div class="card-body p-3">
            <div class="row pt-3">
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Tax Payer</span>
                    <p class="my-1">{{ $location->taxpayer->full_name ?? "" }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Phone Numbers</span>
                    <p class="my-1">{{ $location->taxpayer->mobile ?? "" }}
                        {{ $location->taxpayer->alt_mobile ? "/ " . $location->taxpayer->alt_mobile : "" }} </p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Email</span>
                    <p class="my-1">{{ $location->taxpayer->email ?? "" }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Name</span>
                    <p class="my-1">{{ $location->business->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Business Location</span>
                    <p class="my-1">{{ $location->branch->name ?? "Head Quarter" }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Financial Year</span>
                    <p class="my-1">{{ $location->financialYear->name ?? "" }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="card-header text-uppercase text-danger font-weight-bold bg-white">
            Tax Returns With Risk Indicators
        </div>
        <div class="card-body p-3">
            @if ($taxReturns->isNotEmpty())

                <div id="accordion">
                    @foreach ($taxReturns as $key => $taxReturn)
                        <div class="card">
                            <button class="btn collapsed" type="button" data-toggle="collapse"
                                data-target="#collapse{{ $key }}" aria-expanded="false"
                                aria-controls="collapse{{ $key }}" style="color: #0080c1">
                                <div class="card-header" id="heading{{ $key }}">
                                    <h5 class="mb-0">
                                        {{ $taxReturn->taxType->name }} , {{ $taxReturn->financialMonth->name ?? "" }}
                                        {{ $taxReturn->financialYear->name ?? "" }} <i class="px-3 bi bi-chevron-down"></i>
                                    </h5>
                                </div>
                            </button>

                            <div id="collapse{{ $key }}"
                                class="collapse @if ($loop->first) show @endif"
                                aria-labelledby="heading{{ $key }}" data-parent="#accordion">
                                <div class="card-body">
                                    @switch(true)
                                        @case($taxReturn instanceof \App\Models\Returns\StampDuty\StampDutyReturn)
                                            @include("returns.stamp-duty.details", [
                                                "return" => $taxReturn,
                                            ])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\Petroleum\PetroleumReturn)
                                            @include("returns.petroleum.filing.details", [
                                                "return" => $taxReturn,
                                            ])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\LumpSum\LumpSumReturn)
                                            @include("returns.lump-sum.details", [
                                                "return" => $taxReturn,
                                            ])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\Vat\VatReturn)
                                            @include("returns.vat_returns.details", [
                                                "return" => $taxReturn,
                                            ])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\MmTransfer\MmTransferReturn)
                                            @include("returns.excise-duty.mobile-money-transfer.details", ["return" => $taxReturn])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\Port\PortReturn)
                                            @include("returns.port.details", ["return" => $taxReturn])
                                        @break

                                        @case($taxReturn instanceof \App\Models\Returns\Mno\MnoReturn)
                                            @include("returns.excise-duty.mno.details", [
                                                "return" => $taxReturn,
                                            ])
                                        @break
                                    @endswitch

                                    <h5 class="text-danger mt-4 text-uppercase">Risk Indicators On This Return:</h5>
                                    <ul>
                                        @foreach ($location->taxVerifications->where("taxReturn", $taxReturn)->first()->riskIndicators as $riskIndicator)
                                            <li>{{ $riskIndicator->risk_indicator }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No tax returns found for this location.</p>
            @endif

        </div>
    </div>

    </div>
@endsection
