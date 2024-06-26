
    <div class="card my-2 mx-2 bg-white rounded-0">
        <div class="card-header font-weight-bold bg-white">
            Installment Extension Approval
        </div>
        <div class="card-body">
            @include('livewire.approval.transitions')

            <div class="row mx-1">
                <div class="col-md-12 mb-2">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Comments</label>
                        <textarea class="form-control @error('comments') is-invalid @enderror" wire:model.defer='comments' rows="3"></textarea>

                        @error('comments')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer p-2 m-0">
            <button  class="btn btn-danger"
                    wire:click="confirmPopUpModal('reject','rejected')"
                    wire:loading.attr="disabled">
{{--                <div wire:loading wire:target="reject('rejected')">--}}
{{--                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">--}}
{{--                        <span class="sr-only">Loading...</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
                Reject & Return
            </button>
            <button wire:click="confirmPopUpModal('approve', 'accepted')"
                    wire:loading.attr="disabled" class="btn btn-primary">
{{--                <div wire:loading wire:target="approve('accepted)">--}}
{{--                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">--}}
{{--                        <span class="sr-only">Loading...</span>--}}
{{--                    </div>--}}
{{--                </div>--}}
                Approve
            </button>
        </div>
    </div>
