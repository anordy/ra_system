<div>
    <div class="d-flex justify-content-start mb-3">
        <a href="{{ route('land-lease.list') }}" class="btn btn-info">
            {{-- back icon --}}
            <i class="fas fa-arrow-left"></i>
            Back
        </a>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <livewire:returns.return-payment :return="$landLease" />
            </div>
        </div>
    </div>

    <div class="card">

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
                            {{ $landLease->createdBy->middle_name ?? '' }} {{ $landLease->createdBy->last_name ?? '' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Register At</span>
                        <p class="my-1">{{ $landLease->created_at }}</p>
                    </div>
                    @if ($landLease->edited_by != null)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Edited By</span>
                            <p class="my-1">{{ $landLease->editedBy->first_name ?? '' }}
                                {{ $landLease->createdBy->middle_name ?? '' }} {{ $landLease->editedBy->last_name ?? '' }}</p>
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
</div>
