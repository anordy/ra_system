<div>
    <form wire:submit.prevent="filter">
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="currency" class="d-flex justify-content-between'">
                    <span>Currency</span>
                </label>
                <select name="currency" class="form-control" wire:model.defer="currency">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\Currencies::TZS }}">TZS</option>
                    <option value="{{ \App\Enum\Currencies::USD }}">USD</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label class="d-flex justify-content-between">
                    <span>Start Date</span>
                </label>
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model.defer="range_start">
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
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model.defer="range_end">
                @error('range_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-end">

                    <button class="btn btn-primary ml-2" wire:click="filter " wire:loading.attr="disabled">
                        <i class="bi bi-filter mr-2" wire:loading.remove wire:target="filter"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="filter"></i>
                            Apply Filter
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
