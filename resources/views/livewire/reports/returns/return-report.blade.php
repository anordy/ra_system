<div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="start_month" class="d-flex justify-content-between'">
                <span>
                    Year
                </span>
            </label>
            <select name="year" id="start_month" class="form-control">
                @foreach ($optionYears as $optionYear)
                    <option value="{{ $optionYear }}">{{ $optionYear }}</option>
                @endforeach
            </select>
            @error('year')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror 
        </div>

            <div class="col-md-4 form-group">
                <label for="Period" class="d-flex justify-content-between'">
                    <span>
                        Period
                    </span>
                </label>
                <select name="period" id="Period" wire:model="period"
                    class="form-control ">
                    <option value="" disabled>Select Period</option>
                    {{-- @foreach ($optionPeriods as $optionPeriod)
                        <option value="{{ $optionPeriod }}">
                            {{ $optionPeriod }}</option>
                    @endforeach --}}
                </select>
                {{-- @error('period')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror --}}
            </div>
                <div class="col-md-4 form-group">
                    <label for="Quarter" class="d-flex justify-content-between'">
                        <span>
                            Semi-Annual
                        </span>
                    </label>
                    <select name="semiAnnual" id="Quarter" class="form-control">
                        <option value="" disabled>Select Semi-Annual term</option>
                        {{-- @foreach ($optionSemiAnnuals as $optionSemiAnnual)
                            <option value={{ $optionSemiAnnual }}>
                                {{ $optionSemiAnnual }}</option>
                        @endforeach --}}
                    </select>
                    {{-- @error('semiAnnual')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror --}}
                </div>
        
                <div class="col-md-4 form-group">
                    <label for="Quarter" class="d-flex justify-content-between'">
                        <span>
                            Quarter
                        </span>
                    </label>
                    <select name="quater" id="Quarter" wire:model="quater" class="form-control">
                        <option value="" disabled>Select Quater</option>
                        {{-- @foreach ($optionQuarters as $optionQuarter)
                            <option value={{ $optionQuarter }}>
                                {{ $optionQuarter }}</option>
                        @endforeach --}}
                    </select>
                    {{-- @error('quater')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror --}}
                </div>
        
            
                <div class="col-md-4 form-group">
                    <label for="Month" class="d-flex justify-content-between'">
                        <span>
                            Months
                        </span>
                    </label>
                    <select name="month" id="Month" wire:model="month"
                        class="form-control">
                        <option value="" disabled>Select Month</option>
                        {{-- @foreach ($optionMonths as $key => $optionMonth)
                            <option value={{ $key }}>
                                {{ $optionMonth }}</option>
                        @endforeach --}}
                    </select>
                    {{-- @error('month')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror --}}
                </div>

    </div>
</div>
