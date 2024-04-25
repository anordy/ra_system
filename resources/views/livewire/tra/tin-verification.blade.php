<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        TIN Number Verification
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">TIN Number</span>
                    <p class="my-1">{{ $tinNumber ?? 'N/A' }}</p>
                </div>
                @if (!$tinData)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Action</span>
                        <p class="my-1">
                            <button wire:click="verifyTin" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                                <div wire:loading wire:target="verifyTin">
                                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                Verify TIN Number
                            </button>
                        </p>
                    </div>
                @else
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Status</span>
                        <p class="my-1">
                        <span class="badge badge-success py-1 px-2"
                              style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            TIN IS VALID
                        </span>
                        </p>
                    </div>
                    @if($tinData->is_entity_tin)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN Holder's Name</span>
                            <p class="my-1">{{ $tinData->fullname ?? 'N/A' }}</p>
                        </div>
                    @endif
                    @if($tinData->is_business_tin)
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">TIN Holder's Name</span>
                            <p class="my-1">{{ $tinData->taxpayer_name ?? 'N/A' }}</p>
                        </div>
                    @endif
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Mobile</span>
                        <p class="my-1">{{ $tinData->mobile }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Email</span>
                        <p class="my-1">{{ $tinData->email }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Region</span>
                        <p class="my-1">{{ $tinData->region ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">District</span>
                        <p class="my-1">{{ $tinData->district ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Street</span>
                        <p class="my-1">{{ $tinData->street ?? 'N/A' }}</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>