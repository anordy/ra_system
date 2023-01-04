<div class="row">
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
        <input type="date" min="{{ $range_start ?? $today }}"  max="{{$today }}" class="form-control" wire:model="range_end">
        @error('range_end')
        <div class="text-danger">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mt-4">
        <div class="col-md-12 d-flex justify-content-end">
            <div x-data>
                <button class="btn btn-primary ml-2" wire:click="search" wire:loading.attr="disabled">
                    <i class="fas fa-filter ml-1" wire:loading.remove wire:target="search"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="search"></i> Search
                </button>
            </div>

            @if($hasData)
                <button class="btn btn-success ml-2" wire:click="exportExcel" wire:loading.attr="disabled">
                    <i class="fas fa-file-xlxs ml-1" wire:loading.remove wire:target="exportExcel"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="exportExcel"></i>
                    Export to Excel
                </button>
            @endif
        </div>
    </div>

    @if ($hasData)
    <div class="col-md-12 mt-3">
        @livewire('payments.recon-report-table', ['parameters' => $parameters])
    </div>
    @endif
</div>
