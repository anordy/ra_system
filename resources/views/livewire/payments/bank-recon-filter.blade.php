<div class="row d-flex">
    <div class="flex-grow-1 form-group ml-3 mr-3">
        <label class="d-flex justify-content-between font-weight-bold">
            <span>Start Date</span>
        </label>
        <input type="date" max="{{ $today }}" class="form-control" wire:model="range_start">
        @error('range_start')
            <div class="text-danger">
                {{ $message }}
            </div>
        @enderror
    </div>
    <div class="flex-grow-1 form-group mr-3">
        <label class="d-flex justify-content-between font-weight-bold">
            <span>End Date</span>
        </label>
        <input type="date" min="{{ $range_start ?? $today }}"  max="{{$today }}" class="form-control" wire:model="range_end">
        @error('range_end')
        <div class="text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="flex-grow-1 form-group">
        <label class="font-weight-bold">Currency</label>
        <select class="form-control @error('currency') is-invalid @enderror" wire:model="currency" >
            <option value="all">All</option>
            <option>USD</option>
            <option>TZS</option>
        </select>
        @error('currency')
        <div class="text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="d-flex align-items-end pb-3">
        <div class="col-md-12 d-flex justify-content-end">
            <button class="btn btn-primary mr-2" wire:click="search" wire:loading.attr="disabled">
                <i class="fas fa-filter mr-2" wire:loading.remove wire:target="search"></i>
                <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="search"></i> Search
            </button>

            @if($hasData)
                <button class="btn btn-success mr-2" wire:click="exportExcel" wire:loading.attr="disabled">
                    <i class="bi bi-filetype-xls mr-2" wire:loading.remove wire:target="exportExcel"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="exportExcel"></i>
                    Export to Excel
                </button>
            @endif

            @can('bank-recon-import')
                <button class="btn btn-primary"
                        onclick="Livewire.emit('showModal', 'payments.bank-recon-import-modal')">
                    <i class="bi bi-arrow-bar-down mr-2"></i>
                    Import Bank Reconciliations
                </button>
            @endcan
        </div>
    </div>

    @if ($hasData)
        <div class="col-md-12 mt-3">
            <livewire:payments.bank-recon-table :parameters="$parameters" key="{{ now() }}" />
        </div>
    @endif
</div>
