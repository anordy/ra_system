<div>
    <form wire:submit.prevent="filter">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="currency" class="d-flex justify-content-between'">
                    <span>Currency</span>
                </label>
                <select name="currency" class="form-control" wire:model="currency">
                    <option value="All">All</option>
                    <option value="TZS">TZS</option>
                    <option value="USD">USD</option>
                </select>
            </div>
            
            <div class="col-md-6 form-group">
                <label class="d-flex justify-content-between">
                    <span>Start Date</span>
                </label>
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model="range_start">
                @error('range_start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-md-6 form-group">
                <label class="d-flex justify-content-between'">
                    <span>End Date</span>
                </label>
                <input type="date" min="{{ date('Y-m-d', strtotime($range_start))}}" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model="range_end">
                @error('range_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>


            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-end">

                    <button class="btn btn-primary ml-2" wire:click="filter " wire:loading.attr="disabled">
                        <i class="bi bi-filter ml-1" wire:loading.remove wire:target="filter"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="filter"></i>
                            Filter
                    </button>
    
                    <button class="btn btn-success ml-2" wire:click="pdf" wire:loading.attr="disabled">
                        <i class="fas fa-file-pdf ml-1" wire:loading.remove wire:target="pdf"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="pdf"></i>
                            Export summary
                    </button>      
                </div>
            </div>

        </div>
    </form>
</div>
