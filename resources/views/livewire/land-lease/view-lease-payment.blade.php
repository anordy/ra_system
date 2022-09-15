<div>
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-info">
            {{-- back icon --}}
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @livewire('land-lease.land-lease-payment', ['leasePayment' => $leasePayment])
            </div>
        </div>
    </div>

    <div class="card">
        
        <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
            Lease Payment Information
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Year</span>
                    <p class="my-1">
                        {{ $leasePayment->financialYear->code }}
                    </p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Status</span>
                    <p class="my-1">
                        @if ($leasePayment->status === \App\Enum\LeaseStatus::IN_ADVANCE_PAYMENT)
                            <span class="badge badge-success py-1 px-2"
                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Paid In Advance
                            </span>
                        @elseif ($leasePayment->status === \App\Enum\LeaseStatus::ON_TIME_PAYMENT)
                            <span class="badge badge-success py-1 px-2"
                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Paid On Time
                            </span>
                        @elseif ($leasePayment->status === \App\Enum\LeaseStatus::LATE_PAYMENT)
                            <span class="badge badge-success py-1 px-2"
                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                <i class="bi bi-check-circle-fill mr-1"></i>
                                Paid Late
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATING)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                <i class="bi bi-clock-history mr-1"></i>
                                Control Number Generating
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATED)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                <i class="bi bi-clock-history mr-1"></i>
                                Control Number Generated
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATION_FAILED)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                <i class="bi bi-clock-history mr-1"></i>
                                Control Number Generating Failed
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::PAID_PARTIALLY)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
                                <i class="bi bi-pencil-square mr-1"></i>
                                Paid Partially
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::PENDING)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
                                <i class="bi bi-pencil-square mr-1"></i>
                                Pending
                            </span>
                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::DEBT)
                            <span class="badge badge-danger py-1 px-2"
                                style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
                                <i class="bi bi-record-circle mr-1"></i>
                                Debt
                            </span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Paid At</span>
                    <p class="my-1">
                        {{ $leasePayment->paid_at ?? '--'  }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
            Lease Information
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <span class="font-weight-bold text-uppercase">Payment Month</span>
                    <p class="my-1">
                        {{ $leasePayment->landLease->payment_month }}
                    </p>
                </div>
                <div class="col-md-6">
                    <span class="font-weight-bold text-uppercase">Valid Period Terms</span>
                    <p class="my-1">
                        {{ $leasePayment->landLease->valid_period_term }} Years
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Site Plan Number (DP No.)</span>
                    <p class="my-1">{{ $leasePayment->landLease->dp_number }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Region</span>
                    <p class="my-1">{{ $leasePayment->landLease->region->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">District</span>
                    <p class="my-1">{{ $leasePayment->landLease->district->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Ward</span>
                    <p class="my-1">{{ $leasePayment->landLease->ward->name }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Commence Date</span>
                    <p class="my-1">{{ date('d/m/Y', strtotime($leasePayment->landLease->commence_date)) }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Month</span>
                    <p class="my-1">{{ $leasePayment->landLease->payment_month }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Payment Amount</span>
                    <p class="my-1">{{ number_format($leasePayment->landLease->payment_amount) }} USD</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Review Schedule</span>
                    <p class="my-1">{{ $leasePayment->landLease->review_schedule }} years</p>
                </div>
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Valid Period Term</span>
                    <p class="my-1">{{ $leasePayment->landLease->valid_period_term }} years</p>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <a class="file-item" target="_blank"
                        href="{{ route('land-lease.get.lease.document', ['path' => encrypt($leasePayment->landLease->lease_agreement_path)]) }}">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            Lease Agreement Document
                        </div>
                    </a>
                </div>
            </div>

            <div class="card-footer">
                <div class="row" style="background-color: #f5f2f2">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Registered By</span>
                        <p class="my-1">{{ $leasePayment->landLease->createdBy->first_name ?? '' }}
                            {{ $leasePayment->landLease->createdBy->middle_name ?? '' }}
                            {{ $leasePayment->landLease->createdBy->last_name ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Register At</span>
                        <p class="my-1">{{ $leasePayment->landLease->created_at }}</p>
                    </div>
                    @if ($leasePayment->landLease->edited_by != null)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Edited By</span>
                            <p class="my-1">{{ $leasePayment->landLease->editedBy->first_name ?? '' }}
                                {{ $leasePayment->landLease->createdBy->middle_name ?? '' }}
                                {{ $leasePayment->landLease->editedBy->last_name ?? '' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Edited At</span>
                            <p class="my-1">{{ $leasePayment->landLease->updated_at }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
