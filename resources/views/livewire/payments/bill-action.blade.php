<div>
    <div class="mx-4">
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <b>GEPG STATUS: </b> {{ session()->get('error') }}.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <b>GEPG STATUS: </b> {{ session()->get('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <div class="row mx-4 mt-2">
        <div class="form-group col-md-4">
            <label class="control-label">Control Number</label>
            <input type="text" class="form-control" wire:model.lazy="control_number" id="control_number">
            @error('control_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label>Bill Action</label>
            <select wire:model="action" class="form-control">
                <option selected>Select Action</option>
                <option value="cancel">Cancel Bill</option>
                <option value="update">Update Bill</option>
            </select>
            @error('action')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        @if ($action == 'update')
            <div class="form-group col-md-4">
                <label class="control-label">New Expiration Date</label>
                <input type="date" min="{{ $today }}" class="form-control"
                    wire:model.lazy="new_expiration_date" id="new_expiration_date">
                @error('new_expiration_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        @endif
    </div>

    <div class="row mx-4">

        @if ($action == 'cancel')
            <div class="col-md-12 form-group">
                <label for="cancellation_reason">Cancellation Reason</label>
                <textarea class="form-control @error('cancellation_reason') is-invalid @enderror" wire:model.lazy="cancellation_reason"
                    rows="3"></textarea>
                @error('cancellation_reason')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @endif
    </div>

    <hr>
    <div class="row mx-4">
        <div class="col-md-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" wire:click='billAction' wire:loading.attr="disabled">
                <div wire:loading wire:target="billAction">
                    <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                Submit
            </button>
        </div>
    </div>
</div>
