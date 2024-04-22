<div class="card mt-3">

    <div class="card-body">
            <div class="card-header bg-white font-weight-bold">
                @if ($owner == 'previous') Previous @else New @endif Owner  Details
            </div>
            <div class="card-body">
                <div class="row my-2">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Application Reference No.</span>
                        <p class="my-1">{{ $taxPayer->reference_no }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Full Name</span>
                        <p class="my-1">{{ "{$taxPayer->first_name} {$taxPayer->middle_name} {$taxPayer->last_name}" }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Email Address</span>
                        <p class="my-1">{{ $taxPayer->email ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile</span>
                        <p class="my-1">{{ $taxPayer->mobile }}</p>
                    </div>
                    @if ($taxPayer->alt_mobile)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
                            <p class="my-1">{{ $taxPayer->alt_mobile }}</p>
                        </div>
                    @endif
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Nationality</span>
                        <p class="my-1">{{ $taxPayer->country->nationality }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Region</span>
                        <p class="my-1">{{ $taxPayer->region->name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">District</span>
                        <p class="my-1">{{ $taxPayer->district->name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Ward</span>
                        <p class="my-1">{{ $taxPayer->ward->name ?? 'N/A'}}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Street</span>
                        <p class="my-1">{{ $taxPayer->street->name ?? 'N/A' }}</p>
                    </div>
                </div>
                <hr />
                <div class="row">
                    @if ($taxPayer->nida_no)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">NIDA No.</span>
                            <p class="my-1">{{ $taxPayer->nida_no }}</p>
                        </div>
                    @endif
                    @if ($taxPayer->zanid_no)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">ZANID No.</span>
                            <p class="my-1">{{ $taxPayer->zanid_no }}</p>
                        </div>
                    @endif
                    @if ($taxPayer->passport_no)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Passport No.</span>
                            <p class="my-1">{{ $taxPayer->passport_no }}</p>
                        </div>
                    @endif
                    @if ($taxPayer->permit_number)
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Permit Number</span>
                            <p class="my-1">{{ $taxPayer->permit_number }}</p>
                        </div>
                    @endif
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Location</span>
                        <p class="my-1">{{ $taxPayer->location }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Street</span>
                        <p class="my-1">{{ $taxPayer->street->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <span class="font-weight-bold text-uppercase">Physical Address</span>
                        <p class="my-1">{{ $taxPayer->physical_address }}</p>
                    </div>
                </div>
            </div>
    </div>
</div>

@if ($taxPayer->approval_report)
    <div class="card my-4 rounded-0">
        <div class="card-header font-weight-bold bg-white">
            Approval Report
        </div>
        <div class="card-body">
            <div class="row">
                @if ($taxPayer->approval_report)
                    <div class="col-md-3">
                        <div class="p-2 mb-3 d-flex rounded-sm align-items-center file-item">
                            <i class="bi bi-file-earmark-pdf-fill px-2 file-icon"></i>
                            <a target="_blank"
                               href="{{ route('assesments.waiver.files', encrypt($taxPayer->approval_report)) }}"
                               class="ml-1 font-weight-bolder">
                                Approval Report
                                <i class="bi bi-arrow-up-right-square ml-1"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif