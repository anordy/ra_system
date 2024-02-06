<div>
    <hr>
    <div class="row">
        @if($responsiblePerson->id_number)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">TIN No.</span>
                <p class="my-1">{{ $responsiblePerson->id_number }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                <p class="my-1 {{ $responsiblePerson->id_verified_at ? 'text-success' : 'text-danger' }}">
                    {{ $responsiblePerson->id_verified_at ? 'Verified' : 'Unverified' }}
                </p>
            </div>
        @endif

        @if (empty($responsiblePerson->id_verified_at))
            <div class="col-md-4 mb-3 d-flex align-items-center">
                <button wire:click="verifyTIN" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                    <div wire:loading wire:target="verifyTIN">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Verify TIN No.
                </button>
            </div>
        @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">TIN Verified At</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($responsiblePerson->id_verified_at)->format('d M Y H:i:s') }}</p>
            </div>
        @endif
    </div>

    @if ($is_verified_triggered && $tin)
        @if ($tin)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body mt-0 p-2">
                            <div class="row my-2">
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">First Name</span>
                                    <p class="my-1">{{ $tin['first_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Middle Name</span>
                                    <p class="my-1">{{ $tin['middle_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Last Name</span>
                                    <p class="my-1">{{ $tin['last_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Mobile</span>
                                    <p class="my-1">{{ $tin['mobile'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Email Address</span>
                                    <p class="my-1">{{ $tin['email'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Gender</span>
                                    <p class="my-1">{{ $tin['gender'] }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Date of Birth</span>
                                    <p class="my-1">{{ $tin['date_of_birth'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Taxpayer Name</span>
                                    <p class="my-1">{{ $tin['taxpayer_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Status</span>
                                    <p class="my-1">{{ $tin['status'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
        @endif
    @endif
</div>
