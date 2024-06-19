<div class="card">
    <div class="card-body">
        <div>
            <div class="card-header text-uppercase font-weight-bold bg-white">
                {{ __('Lease Document Verification') }}
            </div>

            <div class="row mt-5">
                <div class="col-4">
                    <a class="file-item" target="_blank"
                       href="{{ route('land-lease.get.lease.document', ['path' => encrypt($previousLeaseAgreementPath)]) }}">
                        <i class="bi bi-file-earmark-pdf-fill px-2" style="font-size: x-large"></i>
                        <div style="font-weight: 500;" class="ml-1">
                            {{ __('Lease Agreement Document (Preview)') }}
                        </div>
                    </a>
                </div>
                <div class="col-8">
                    <label> <b>{{ __('Comments (Optional)') }}</b></label>
                    <textarea class="form-control" rows="4" placeholder="comments (optional)"
                              name="comments" wire:model.lazy="comments"></textarea>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" wire:model.lazy="confirmed"
                               id="confirmationCheck"
                               required>
                        <label class="form-check-label text-danger" for="confirmationCheck">
                            {{ __('I confirm that I have reviewed the document and chose to approve/reject this request
                            .') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 d-flex justify-content-start">
                <button class="btn btn-warning ml-1" @if(!$confirmed) disabled @endif wire:click="submit('approved')" wire:loading.attr="disabled">
                    {{ __('Approve Lease') }}
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="submit"></i>
                </button>
            </div>
            <div class="col-md-12 d-flex justify-content-start mt-3">
                <button class="btn btn-danger ml-1" @if(!$confirmed) disabled @endif wire:click="submit('rejected')"
                        wire:loading.attr="disabled">
                    {{ __('Reject Lease') }}
                    <i class="bi bi-chevron-right ml-1" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                       wire:target="submit"></i>
                </button>
            </div>
        </div>
    </div>
</div>
