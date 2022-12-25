<div>
    <form wire:submit.prevent="fillter">
        <div class="row">

            <div class="col-md-6 form-group">
                <label for="tax_type" class="d-flex justify-content-between'">
                    <span>Tax Type</span>
                </label>
                <select name="tax_type_id" class="form-control" wire:model="tax_type_id">
                    <option value="All">All</option>
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
                    <option value="All">All</option>
                    <option value="TZS">TZS</option>
                    <option value="USD">USD</option>
                </select>
            </div>

            <div class="col-md-6 form-group">
                <label class="d-flex justify-content-between'">
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
                    <button type="submit" class=" btn btn-success ml-2 px-2" wire:click='pdf()'
                        wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target='pdf'>
                            <i class="fas fa-file-pdf"></i>
                            Export summary
                        </div>
                        <div wire:loading wire:target='pdf'>
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status"> </div>
                            Loading...
                        </div>
                    </button>

                    <button type="submit" class=" btn btn-primary ml-2 px-2" wire:click='fillter()'
                    wire:loading.attr="disabled">
                    <div wire:loading.remove wire:target='fillter'>
                        <i class="fa fa-filter"></i>
                        Fillter
                    </div>
                    <div wire:loading wire:target='fillter'>
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status"> </div>
                        Loading...
                    </div>
                </button>
                </div>
            </div>

        </div>
    </form>
</div>
