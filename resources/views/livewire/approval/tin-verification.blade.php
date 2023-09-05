<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $business->name }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">TIN No.</span>
            <p class="my-1">{{ $business->tin ?? 'N/A' }}</p>
        </div>
        @if ($business->tin_verification_status === \App\Enum\TinVerificationStatus::PENDING)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Action</span>
                <p class="my-1">
                    <button wire:click="validateTinNumber" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                        <div wire:loading wire:target="validateTinNumber">
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>Verify Data From TRA
                    </button>
                </p>
            </div>
        @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                @if ($business->tin_verification_status === \App\Enum\TinVerificationStatus::UNVERIFIED)
                    <p class="my-1">
                        <span class="badge badge-danger py-1 px-2 text-capitalize"
                            style="border-radius: 1rem; background: #fde047; color: #a16207; font-size: 85%">
                            <i class="bi bi-record-circle mr-1"></i>
                            {{ $business->tin_verification_status }}
                        </span>
                    </p>
                @elseif($business->tin_verification_status === \App\Enum\TinVerificationStatus::APPROVED)
                    <p class="my-1">
                        <span class="badge badge-success py-1 px-2"
                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Verification Successful
                        </span>
                    </p>
                @endif
            </div>
        @endif

    </div>

    @if ($tin)
        @if ($business->tin_verification_status === \App\Enum\TinVerificationStatus::PENDING)
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body mt-0 p-2">
                        <div class="row my-2">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">TIN</span>
                                <p class="my-1">{{ $tin['tin'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">First Name</span>
                                <p class="my-1">{{ $tin['first_name'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Middle Name</span>
                                <p class="my-1">{{ $tin['middle_name'] ?? '' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Last Name</span>
                                <p class="my-1">{{ $tin['last_name'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $tin['email'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $tin['mobile'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Gender</span>
                                <p class="my-1">{{ $tin['gender'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Taxpayer Name</span>
                                <p class="my-1">{{ $tin['taxpayer_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Trading Name</span>
                                <p class="my-1">{{ $tin['trading_name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">District</span>
                                <p class="my-1">{{ $tin['district'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Region</span>
                                <p class="my-1">{{ $tin['region'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Street</span>
                                <p class="my-1">{{ $tin['street'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Postal Code</span>
                                <p class="my-1">{{ $tin['postal_code'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Plot Number</span>
                                <p class="my-1">{{ $tin['plot_number'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Vat Registration Number</span>
                                <p class="my-1">{{ $tin['vat_registration_number'] }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Plot Number</span>
                                <p class="my-1">{{ $tin['plot_number'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-8 mb-3">
                                <span class="font-weight-bold text-uppercase">Postal Address</span>
                                <p class="my-1">{{ $tin['postal_address'] }}</p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer p-2 m-0">
                        <button wire:click="save()" wire:loading.attr="disabled" class="btn btn-success">
                            <div wire:loading wire:target="confirm">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>Save TIN Information
                        </button>
                    </div>

                </div>

            </div>
            <hr>
        @endif
    @endif



</div>
