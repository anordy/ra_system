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
                {{-- @livewire('land-lease.land-lease-payment', ['landLease' => $landLease]) --}}
                {{-- <livewire:returns.land-lease-payment :return="$landLease" /> --}}
            </div>
        </div>
    </div>

    <div class="card">
        <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#lease-infos" role="tab"
                    aria-controls="lease-infos" aria-selected="true">Lease Informations</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="lease-payments-tab" data-toggle="tab" href="#lease-payments" role="tab"
                    aria-controls="lease-payments" aria-selected="false">Lease Payments</a>
            </li>

        </ul>
        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
            <div class="tab-pane fade show active" id="lease-infos" role="tabpanel" aria-labelledby="lease-infos-tab">

                <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                    Applicant Profile
                </div>

                @if ($landLease->category == 'business')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Name</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->business->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Business Location Name</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->business->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Physical Address</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->physical_address }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Region</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->region->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">District</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->district->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Ward</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->ward->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Street</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->street }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Name</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->first_name }} {{ $landLease->taxpayer->last_name }}
                                    @else
                                        {{ $landLease->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Phone Number</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->mobile }}
                                    @else
                                        {{ $landLease->phone }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->email }}
                                    @else
                                        {{ $landLease->email }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Address</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->physical_address }}
                                    @else
                                        {{ $landLease->address }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Applicant Type</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">

                                            Registered
                                        </span>
                                    @else
                                        <span class="badge badge-danger py-1 px-2"
                                            style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">

                                            Not Registered
                                        </span>
                                    @endif
                                </p>
                            </div>
                            @if ($landLease->is_registered)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">ZRB reference no.</span>
                                    <p class="my-1">
                                        {{ $landLease->taxpayer->reference_no }}
                                    </p>
                                </div>
                            @endif
                        </div>


                    </div>
                @endif

                <div class="pt-5"></div>
                <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                    Lease Information
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Site Plan Number (DP No.)</span>
                            <p class="my-1">{{ $landLease->dp_number }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Region</span>
                            <p class="my-1">{{ $landLease->region->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">District</span>
                            <p class="my-1">{{ $landLease->district->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Ward</span>
                            <p class="my-1">{{ $landLease->ward->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Commence Date</span>
                            <p class="my-1">{{ date('d/m/Y', strtotime($landLease->commence_date)) }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Month</span>
                            <p class="my-1">{{ $landLease->payment_month }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Payment Amount</span>
                            <p class="my-1">{{ number_format($landLease->payment_amount) }} USD</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Review Schedule</span>
                            <p class="my-1">{{ $landLease->review_schedule }} years</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Valid Period Term</span>
                            <p class="my-1">{{ $landLease->valid_period_term }} years</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <a class="file-item" target="_blank"
                                href="{{ route('land-lease.get.lease.document', ['path' => encrypt($landLease->lease_agreement_path)]) }}">
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
                                <p class="my-1">{{ $landLease->createdBy->first_name ?? '' }}
                                    {{ $landLease->createdBy->middle_name ?? '' }}
                                    {{ $landLease->createdBy->last_name ?? '' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Register At</span>
                                <p class="my-1">{{ $landLease->created_at }}</p>
                            </div>
                            @if ($landLease->edited_by != null)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Edited By</span>
                                    <p class="my-1">{{ $landLease->editedBy->first_name ?? '' }}
                                        {{ $landLease->createdBy->middle_name ?? '' }}
                                        {{ $landLease->editedBy->last_name ?? '' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Edited At</span>
                                    <p class="my-1">{{ $landLease->updated_at }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>

            <div class="tab-pane fade" id="lease-payments" role="tabpanel" aria-labelledby="lease-payments-tab">
                <div class="card shadow-sm my-4 rounded-0">
                    <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                        Land Lease Payments
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-weight-bold text-uppercase">Payment Month</span>
                                        <p class="my-1">
                                            {{ $landLease->payment_month }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="font-weight-bold text-uppercase">Valid Period Terms</span>
                                        <p class="my-1">
                                            {{ $landLease->valid_period_term }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body mt-0 p-2">
                                <table class="table table-md">
                                    <thead>
                                        <tr>
                                            <th>Year</th>
                                            <th>Amount</th>
                                            <th>Penalty Amount</th>
                                            <th>Total Amount</th>
                                            <th>Outstanding Amount</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($landLease->leasePayments))
                                            @foreach ($landLease->leasePayments as $leasePayment)
                                                <tr>
                                                    <td>{{ $leasePayment->financialYear->code }}</td>
                                                    <td>
                                                        {{ number_format($leasePayment->total_amount, 2) }}
                                                        {{ $leasePayment->currency }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($leasePayment->penalty, 2) }}
                                                        {{ $leasePayment->currency }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($leasePayment->total_amount_with_penalties, 2) }}
                                                        {{ $leasePayment->currency }}
                                                    </td>
                                                    <td>
                                                        {{ number_format($leasePayment->outstanding_amount, 2) }}
                                                        {{ $leasePayment->currency }}
                                                    </td>
                                                    <td>
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
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::DEBT)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
                                                                <i class="bi bi-record-circle mr-1"></i>
                                                                Debt
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('land-lease.view.lease.payment', encrypt($leasePayment->id)) }}"
                                                            class="btn btn-outline-secondary btn-sm">
                                                            <i class="bi bi-eye-fill mr-1"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    No lease Payments.
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
</div>
