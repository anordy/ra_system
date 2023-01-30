<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="currency" class="d-flex justify-content-between'">
                <span>Currency</span>
            </label>
            <select name="currency" class="form-control" wire:model="currency">
                <option value="all">All</option>
                <option value="TZS">TZS</option>
                <option value="USD">USD</option>
            </select>
        </div>

        <div class="col-md-4 form-group">
            <label for="payment_status" class="d-flex justify-content-between'">
                <span>Payment Status</span>
            </label>
            <select name="payment_status" class="form-control" wire:model="payment_status">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="unpaid">Unpaid</option>
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="payment_status" class="d-flex justify-content-between'">
                <span>Charge Type</span>
            </label>
            <select name="charges_type" class="form-control" wire:model="charges_type">
                <option value="all">All</option>
                <option value="charges-included">Charges Included</option>
                <option value="charges-excluded">Charges Excluded</option>
            </select>
        </div>

        <div class="col-md-4 form-group">
            <label class="d-flex justify-content-between'">
                <span>Start Date</span>
            </label>
            <input type="date" max="{{ $today }}" class="form-control" wire:model="range_start">
            @error('range_start')
            <div class="text-danger">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-group">
            <label class="d-flex justify-content-between'">
                <span>End Date</span>
            </label>
            <input type="date" min="{{ $range_start ?? $today }}" max="{{$today }}" class="form-control"
                wire:model="range_end">
            @error('range_end')
            <div class="text-danger">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12 d-flex justify-content-end">
            <div x-data>
                <button class="btn btn-primary ml-2" wire:click="search" wire:loading.attr="disabled">
                    <i class="fas fa-filter ml-1" wire:loading.remove wire:target="search"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="search"></i> Search
                </button>
            </div>

            @if($hasData)
            <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                <i class="fas fa-file-xlxs ml-1" wire:loading.remove wire:target="exportExcel"></i>
                <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                    wire:target="exportExcel"></i>
                Export to Excel
            </button>
            @endif
        </div>
    </div>

    @if ($hasData)
    <div class="col-md-12 mt-3">
        <livewire:payments.ega-charges-table :parameters="$parameters" key="{{ now() }}" />
    </div>
    @endif

</div>