<div>
    <div class="d-flex justify-content-start mb-3">
        @if ($this->landLease->taxpayer_id)
            @if ($unpaidLease)
                {{--                <div class="alert alert-warning" role="alert">--}}
                {{--                    {{ __('Complete pending payment to proceed with next one!') }}--}}
                {{--                </div>--}}
            @else
                @if(!$advancePaymentStatus)
                    <button class="btn btn-primary btn-md"
                            onclick="Livewire.emit('showModal', 'land-lease.create-lease-payment', {{$landLease}})">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('Create Lease Payment') }}
                    </button>
                @endif

            @endif
        @else
            <div class="alert alert-warning" role="alert">
                {{ __('Applicant must have ZIDRAS account to create lease payment!') }}
            </div>
        @endif
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
                                    {{ $landLease->businessLocation->street->name ?? 'N/A' }}
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
                                    <span class="font-weight-bold text-uppercase">ZRA reference no.</span>
                                    <p class="my-1">
                                        {{ $landLease->taxpayer->reference_no }}
                                    </p>
                                </div>
                            @endif
                        </div>


                    </div>
                @endif

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
                            <span class="font-weight-bold text-uppercase">Commence Date</span>
                            <p class="my-1">{{ \Carbon\Carbon::parse($landLease->rent_commence_date)->toFormattedDateString() }}</p>
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
                            <span class="font-weight-bold text-uppercase">Valid Period Term</span>
                            <p class="my-1">{{ $landLease->valid_period_term }} Year(s)</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Expire Date') }}</span>
                            <p class="my-1">{{ $dueDate }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Lease For') }}</span>
                            <p class="my-1">{{ $landLease->lease_for }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Area') }}</span>
                            <p class="my-1">{{ number_format($landLease->area, 2) }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Lease Status</span>
                            <p class="my-1">
                                @if ($landLease->lease_status == "1")
                                    <span class="badge badge-success py-1 px-2"
                                          style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">

                                            Active
                                        </span>
                                @else
                                    <span class="badge badge-danger py-1 px-2"
                                          style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">

                                            Inactive
                                        </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                        Lease Attachments
                    </div>


                    <div class="row mt-3">
                        @foreach($leaseDocuments as $file)
                            <div class="col-md-3 mb-3">
                                <a class="file-item" target="_blank"
                                   href="{{ route('land-lease.get.lease.document', ['path' => encrypt
                                   ($file->file_path)]) }}">
                                    <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                    <div style="font-weight: 500;" class="ml-1">
                                        {{$file->name}}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer">
                        <div class="row" style="background-color: #f5f2f2">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Registered By</span>
                                <p class="my-1">{{ $landLease->completedBy->fname ?? '' }}
                                    {{ $landLease->completedBy->lname ?? '' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">Register At</span>
                                <p class="my-1">{{ $landLease->completed_at }}</p>
                            </div>
                            @if ($landLease->edited_by != null)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">Edited By</span>
                                    <p class="my-1">{{ $landLease->editedBy->fname ?? '' }}
                                        {{ $landLease->editedBy->lname ?? '' }}</p>
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
                                    <div class="col-md-3">
                                        <span class="font-weight-bold text-uppercase">Payment Month</span>
                                        <p class="my-1">
                                            {{ $landLease->payment_month }}
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="font-weight-bold text-uppercase">Valid Period Terms</span>
                                        <p class="my-1">
                                            {{ $landLease->valid_period_term }} Years
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="font-weight-bold text-uppercase">{{ __('Expire Date') }}</span>
                                        <p class="my-1">
                                            {{ $dueDate }}
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
                                        @php
                                            $previousPaidAt = true; // Initial value to display "View" button for the first payment
                                        @endphp
                                        @foreach ($landLease->leasePayments as $index => $leasePayment)
                                            @php
                                                $canView = $previousPaidAt;
                                                $previousPaidAt = !is_null($leasePayment->paid_at);
                                            @endphp
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
                                                </td>
                                                <td>
                                                    @if ($canView)
                                                        <a href="{{ route('land-lease.view.lease.payment', encrypt($leasePayment->id)) }}"
                                                           class="btn btn-outline-secondary btn-sm">
                                                            <i class="bi bi-eye-fill mr-1"></i> View
                                                        </a>
                                                    @endif
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
