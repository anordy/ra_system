<div>
    <form wire:submit.prevent="filter">
        <div class="row">
            @if ($year == 'Custom Range')
                <div class="col-md-3 form-group">
                    <label for="payment_type" class="d-flex justify-content-between'">
                        <span> Return Type</span>
                    </label>
                    <select name="payment_type" id="payment_type" class="form-control" wire:model="payment_type">
                        <option value="all">All</option>
                        <option value="normal">Normal Return</option>
                        <option value="debt">Debt Return</option>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="year" class="d-flex justify-content-between'">
                        <span>Return Year</span>
                    </label>
                    <select name="year" class="form-control" wire:model="year">
                        @if(!empty($optionYears))
                            @foreach ($optionYears as $optionYear)
                                <option value="{{ $optionYear }}">
                                    {{ $optionYear }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-3 form-group">
                    <label for="month" class="d-flex justify-content-between'">
                        <span>From:</span>
                    </label>
                    <input type="date" name="from" class="form-control" wire:model="from">
                </div>

                <div class="col-md-3 form-group">
                    <label for="month" class="d-flex justify-content-between'">
                        <span>To:</span>
                    </label>
                    <input type="date" name="to" class="form-control" wire:model="to">
                </div>
            @else
                <div class="col-md-4 form-group">
                    <label for="payment_type" class="d-flex justify-content-between'">
                        <span> Return Type</span>
                    </label>
                    <select name="payment_type" id="payment_type" class="form-control" wire:model="payment_type">
                        <option value="all">All</option>
                        <option value="normal">Normal Return</option>
                        <option value="debt">Debt Return</option>
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label for="year" class="d-flex justify-content-between'">
                        <span>Return Year</span>
                    </label>
                    <select name="year" class="form-control" wire:model="year">
                        @if(!empty($optionYears))
                            @foreach ($optionYears as $optionYear)
                                <option value="{{ $optionYear }}">
                                    {{ $optionYear }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4 form-group">
                    <label for="month" class="d-flex justify-content-between'">
                        <span>Return Month</span>
                    </label>
                    <select name="month" class="form-control" wire:model="month">
                        <option value="all">All</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
            @endif


            <div class="col-md-12 text-center">
                <div class="d-flex justify-content-end">
                    <button type="submit" class=" btn btn-primary ml-2 px-5" wire:click='filter()'
                        wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target='filter'>
                            <i class="bi bi-filter"></i>
                            Fillter
                        </div>
                        <div wire:loading wire:target='filter'>
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status"></div>
                            Loading...
                        </div>
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
