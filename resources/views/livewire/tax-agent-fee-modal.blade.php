<div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding fee configuration for taxagent</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Category</label>
                            <select wire:model.lazy="category" name="category" id="category" class="form-control">
                                <option  value="">select category</option>
                                <option value="first fee">first fee</option>
                                <option value="renewal fee">renewal fee</option>

                            </select>
                            @error('category')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        @if($category == 'renewal fee')
                        <div class="form-group col-lg-6">
                            <label class="control-label">Duration</label>
                            <select wire:model="duration" class="form-control">
                                <option value="">select duration</option>
                                <option value="yearly">Yearly</option>
                                <option value="monthly">Monthly</option>
                                <option value="daily">Daily</option>
                            </select>
                            @error('duration')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        @if($duration == 'daily')
                        <div class="form-group col-lg-6">
                            <label class="control-label">Number of days</label>
                            <select wire:model="no_of_days" class="form-control">
                                <option value="">select days</option>
                                <option value="1 day">1 day</option>
                                @for($i=2; $i<=31; $i++)
                                    <option value="{{$i}} days">{{$i}} days</option>
                                @endfor
                            </select>
                                @error('no_of_days')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        @elseif($duration == 'monthly')
                            <div class="form-group col-lg-6">
                                <label class="control-label">Number of months</label>
                                <select wire:model="no_of_months" class="form-control">
                                    <option value="">select months</option>
                                    <option value="1 month">1 month</option>
                                    @for($i=2; $i<=12; $i++)
                                        <option value="{{$i}} months">{{$i}} months</option>
                                    @endfor
                                </select>
                                @error('no_of_months')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif($duration == 'yearly')
                            <div class="form-group col-lg-6">
                                <label class="control-label">Number of years</label>
                                <select wire:model="no_of_years" class="form-control">
                                    <option value="">select years</option>
                                    <option value="1 year">1 year</option>
                                    @for($i=2; $i<=5; $i++)
                                        <option value="{{$i}} years">{{$i}} years</option>
                                    @endfor
                                    <option value="more than five years">more than five years</option>
                                </select>
                                @error('no_of_days')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif

                        <div class="form-group col-lg-6">
                            <label class="control-label">Amount</label>
                            <input type="text" class="form-control" wire:model.lazy="amount" id="amount">
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save</button>
            </div>
        </div>
    </div>
</div>
