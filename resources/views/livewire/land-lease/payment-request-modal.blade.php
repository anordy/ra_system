<div>
    <button wire:click="$set('showModal', true)" class="btn btn-primary">Make Payment
        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading></i>
    </button>

    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Land Lease Payment</h5>
                        <button type="button" class="close" wire:click="$set('showModal', false)" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <span class="text-uppercase"><b>Original Amount </b></span>
                                        <p class="text-start">{{ number_format
                                            ($landLease->total_amount_with_penalties, 2)
                                            }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-uppercase"><b>Paid Amount </b></span>
                                        <p class="text-start">{{ number_format($landLease->paid_amount, 2)
                                            }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="text-uppercase"><b>Unpaid Amount </b></span>
                                        <p class="text-start">{{ number_format($landLease->outstanding_amount,
                                             2) }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="text-danger">{{ _("Note:") }}</span>
                                    <p class="text-danger"><small>*</small>{{_(" You can make partial payment or full
                                     payment ")
                                    }}</p>
                                </div>
                                <div class="row">
                                    <div class=" form-group pt-3 col-md-12">
                                        <span>{{ _("Enter Amount for Land Lease Payment:") }}</span>
                                        <input x-data x-mask:dynamic="$money($input)" type="text" wire:model="amount"
                                               class="form-control py-3"
                                               id="amount">
                                        @error("amount")
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror <br>
                                        <button type="submit" class="btn btn-primary pt-2">Submit
                                            <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                                               wire:target="submit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

