<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Extend Property Payments Due Date</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">

                        <div class="form-group col-lg-12">
                            <p>
                                <b>Note: </b> Extending due date will affect all unpaid property payments that are less than new due date
                            </p>
                            <label class="control-label">New Due Date</label>
                            <input type="date" class="form-control" wire:model.defer="dueDate"
                                   autocomplete="given-name">
                            @error('dueDate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>