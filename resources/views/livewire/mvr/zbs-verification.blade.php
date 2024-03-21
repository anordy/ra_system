<div class="card mt-3">
    <div class="card-header font-weight-bold bg-white">
        ZBS COR Verification
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Chassis Number</span>
                    <p class="my-1">{{ $chassisNumber ?? 'N/A' }}</p>
                </div>
                @if (!$zbsData)
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Action</span>
                        <p class="my-1">
                            <button wire:click="verifyZbs" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                                <div wire:loading wire:target="verifyZbs">
                                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                                Verify COR
                            </button>
                        </p>
                    </div>
                @else
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Status</span>
                        <p class="my-1">
                        <span class="badge badge-success py-1 px-2 green-status">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            COR PASS
                        </span>
                        </p>
                    </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">COR Number</span>
                            <p class="my-1">{{ $zbsData['cor_number'] ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <span class="font-weight-bold text-uppercase">Inspected Mileage</span>
                            <p class="my-1">{{ number_format($zbsData['mileage']) ?? 'N/A' }} KM</p>
                        </div>
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Inspection Date</span>
                        <p class="my-1">{{ \Carbon\Carbon::create($zbsData['inspection_date'])->format('d M Y') }}</p>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>