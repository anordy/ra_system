<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">
                   {{ __(' Create Lease For') }}  {{ $landLease['dp_number'] }}
                </h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="col-lg-12 form-group">
                        <div class="text-center h5">
                           {{ __(' Create Land lease payment') }} for {{$landLease['dp_number']}} , year {{$displayYear}}
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger px-2" data-dismiss="modal">Close</button>
                <button class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
                    <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
                        wire:target="submit"></i>
                        {{ __('Submit') }}
                </button>
            </div>
        </div>
    </div>
</div>