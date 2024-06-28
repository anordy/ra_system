<div>
    <div class="d-flex mb-3 row">
        <div class="col-md-6">
            <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-info">
                {{-- back icon --}}
                <i class="fas fa-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>
        
        <div class="col-md-6 d-flex justify-content-end">
            @if ($this->landLease->taxpayer_id)
                @if ($unpaidLease)
                    <div class="alert alert-warning" role="alert">
                        {{ __('Complete pending payment to proceed with next one!') }}
                    </div>
                @else
                    <button class="btn btn-primary btn-md"
                        onclick="Livewire.emit('showModal', 'land-lease.create-lease-payment', {{$landLease}})">
                        <i class="fa fa-plus-circle"></i>
                        {{ __('Create Lease Payment') }}
                    </button>
                @endif    
            @else
                <div class="alert alert-warning" role="alert">
                    {{ __('Applicant must have ZIDRAS account to create lease payment!') }}
                </div>
            @endif
        </div>
    </div>

    <div class="card">
        <ul class="nav nav-tabs shadow-sm" id="myTab" role="tablist" style="margin-bottom: 0;">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#lease-infos" role="tab"
                    aria-controls="lease-infos" aria-selected="true">{{ __('Lease Informations') }}</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="lease-payments-tab" data-toggle="tab" href="#lease-payments" role="tab"
                    aria-controls="lease-payments" aria-selected="false">{{ __('Lease Payments') }}</a>
            </li>

        </ul>
        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
            <div class="tab-pane fade show active" id="lease-infos" role="tabpanel" aria-labelledby="lease-infos-tab">

                <div class="card-header text-uppercase font-weight-bold bg-white pt-1">
                    {{ __('Applicant Profile') }}
                </div>

                @if ($landLease->category == 'business')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Business Name') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->business->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Business Location Name') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->business->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Physical Address') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->physical_address }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Region') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->region->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('District') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->district->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Ward') }}</span>
                                <p class="my-1">
                                    {{ $landLease->businessLocation->ward->name }}
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Street') }}</span>
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
                                <span class="font-weight-bold text-uppercase">{{ __('Name') }}</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->first_name }} {{ $landLease->taxpayer->last_name }}
                                    @else
                                        {{ $landLease->name }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Phone Number') }}</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->mobile }}
                                    @else
                                        {{ $landLease->phone }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Email') }}</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->email }}
                                    @else
                                        {{ $landLease->email }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Address') }}</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        {{ $landLease->taxpayer->physical_address }}
                                    @else
                                        {{ $landLease->address }}
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Applicant Type') }}</span>
                                <p class="my-1">
                                    @if ($landLease->is_registered)
                                        <span class="badge badge-success py-1 px-2"
                                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">

                                            {{ __('Registered') }}
                                        </span>
                                    @else
                                        <span class="badge badge-danger py-1 px-2"
                                            style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">

                                            {{ __('Not Registered') }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            @if ($landLease->is_registered)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('ZRA reference no') }}.</span>
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
                    {{ __('Lease Information') }}
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Site Plan Number') }} (DP No.)</span>
                            <p class="my-1">{{ $landLease->dp_number }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Region') }}</span>
                            <p class="my-1">{{ $landLease->region->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('District') }}</span>
                            <p class="my-1">{{ $landLease->district->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Ward')}}</span>
                            <p class="my-1">{{ $landLease->ward->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Commence Date') }}</span>
                            <p class="my-1">{{ date('d/m/Y', strtotime($landLease->commence_date)) }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Payment Month') }}</span>
                            <p class="my-1">{{ $landLease->payment_month }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Payment Amount') }}</span>
                            <p class="my-1">{{ number_format($landLease->payment_amount) }} USD</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Review Schedule') }}</span>
                            <p class="my-1">{{ $landLease->review_schedule }} years</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">{{ __('Valid Period Term') }}</span>
                            <p class="my-1">{{ $landLease->valid_period_term }} years</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <a class="file-item" target="_blank"
                                href="{{ route('land-lease.get.lease.document', ['path' => encrypt($landLease->lease_agreement_path)]) }}">
                                <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                                <div style="font-weight: 500;" class="ml-1">
                                    {{ __('Lease Agreement Document') }}
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row" style="background-color: #f5f2f2">
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Registered By') }}</span>
                                <p class="my-1">{{ $landLease->createdBy->first_name ?? '' }}
                                    {{ $landLease->createdBy->middle_name ?? '' }}
                                    {{ $landLease->createdBy->last_name ?? '' }}</p>
                            </div>
                            <div class="col-md-3 mb-3">
                                <span class="font-weight-bold text-uppercase">{{ __('Register At') }}</span>
                                <p class="my-1">{{ $landLease->created_at }}</p>
                            </div>
                            @if ($landLease->edited_by != null)
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Edited By') }}</span>
                                    <p class="my-1">{{ $landLease->editedBy->first_name ?? '' }}
                                        {{ $landLease->createdBy->middle_name ?? '' }}
                                        {{ $landLease->editedBy->last_name ?? '' }}</p>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <span class="font-weight-bold text-uppercase">{{ __('Edited At') }}</span>
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
                        {{ __('Land Lease Payments') }}
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-weight-bold text-uppercase">{{ __('Payment Month') }}</span>
                                        <p class="my-1">
                                            {{ $landLease->payment_month }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="font-weight-bold text-uppercase">{{ __('Valid Period Terms') }}</span>
                                        <p class="my-1">
                                            {{ $landLease->valid_period_term }} {{ __('Year(s)') }}
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
                                            <th>{{ __('Year') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Penalty Amount') }}</th>
                                            <th>{{ __('Total Amount') }}</th>
                                            <th>{{ __('Outstanding Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
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
                                                                {{ __('Paid In Advance') }}
                                                            </span>
                                                        @elseif ($leasePayment->status === \App\Enum\LeaseStatus::ON_TIME_PAYMENT)
                                                            <span class="badge badge-success py-1 px-2"
                                                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                                {{ __('Paid On Time') }}
                                                            </span>
                                                        @elseif ($leasePayment->status === \App\Enum\LeaseStatus::LATE_PAYMENT)
                                                            <span class="badge badge-success py-1 px-2"
                                                                style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                                                                <i class="bi bi-check-circle-fill mr-1"></i>
                                                                {{ __('Paid Late') }}
                                                            </span>
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATING)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                                                <i class="bi bi-clock-history mr-1"></i>
                                                                {{ __('Control Number Generating') }}
                                                            </span>
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATED)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                                                <i class="bi bi-clock-history mr-1"></i>
                                                                {{ __('Control Number Generated') }}
                                                            </span>
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::CN_GENERATION_FAILED)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
                                                                <i class="bi bi-clock-history mr-1"></i>
                                                                {{ __('Control Number Generating Failed') }}
                                                            </span>
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::PAID_PARTIALLY)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
                                                                <i class="bi bi-pencil-square mr-1"></i>
                                                                {{ __('Paid Partially') }}
                                                            </span>
                                                        @elseif($leasePayment->status === \App\Enum\LeaseStatus::PENDING)
                                                            <span class="badge badge-danger py-1 px-2"
                                                                style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
                                                                <i class="bi bi-pencil-square mr-1"></i>
                                                                {{ __('Pending') }}
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
                                                            <i class="bi bi-eye-fill mr-1"></i> {{ __('View') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-3">
                                                    {{ __('No lease Payments') }}.
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
