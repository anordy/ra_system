<div>
    <form wire:submit.prevent="fillter">
        <div class="row">

            <div class="col-md-6 form-group">
                <label for="tax_type" class="d-flex justify-content-between'">
                    <span>Tax Type</span>
                </label>
                <select name="tax_type_id" class="form-control" wire:model="tax_type_id">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    @foreach ($tax_types as $tax_type)
                        <option value="{{ $tax_type->id }}">
                            {{ $tax_type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label for="currency" class="d-flex justify-content-between'">
                    <span>Currency</span>
                </label>
                <select name="currency" class="form-control" wire:model="currency">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\Currencies::TZS }}">TZS</option>
                    <option value="{{ \App\Enum\Currencies::USD }}">USD</option>
                </select>
            </div>

            <div class="col-md-4 form-group">
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
            <div class="col-md-4 form-group">
                <label class="d-flex justify-content-between'">
                    <span>End Date</span>
                </label>
                <input type="date" max="{{ now()->format('Y-m-d') }}" class="form-control" wire:model="range_end">
                @error('range_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-4 form-group">
                <label for="pbz_status" class="d-flex justify-content-between'">
                    <span>PBZ Status</span>
                </label>
                <select name="pbz_status" class="form-control" wire:model="pbz_status">
                    <option value="{{ \App\Enum\GeneralConstant::ALL }}">All</option>
                    <option value="{{ \App\Enum\GeneralConstant::NOT_APPLICABLE }}">N/A</option>
                    <option value="{{ \App\Enum\GeneralConstant::PAID }}">Paid</option>
                    <option value="{{ \App\Enum\GeneralConstant::REVERSED }}">Reversed</option>
                </select>
            </div>


            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-end">

                    <button class="btn btn-primary ml-2" wire:click="fillter " wire:loading.attr="disabled">
                        <i class="bi bi-filter mr-2" wire:loading.remove wire:target="fillter"></i>
                        <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading wire:target="fillter"></i>
                            Apply Filter
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
