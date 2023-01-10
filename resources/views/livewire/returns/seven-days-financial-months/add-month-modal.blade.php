<div>
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase text-center">Adding seven days financial month</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6">
                        <label class="control-label">Year</label>
                        <select wire:model.lazy="year" name="year" id="year" class="form-control">
                            <option value="">select year</option>
                            @foreach($years as $year)
                                <option value="{{$year->id}}">{{$year->code}}</option>
                            @endforeach
                        </select>
                        @error('year')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Month</label>
                        <select wire:model="month_number" class="form-control">
                            <option value="">select month</option>
                            @for($x = 1; $x <= 12; $x ++)
                                <option value="{{$x}}">{{date( 'F', strtotime( "$x/12/10" ))}}</option>
                            @endfor
                        </select>
                        @error('month_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="control-label">Days</label>
                        <select wire:model="day" class="form-control">
                            <option value="">select day</option>
                            @if($is_leap)
                                @for($x = 1; $x <= 29; $x ++)
                                    <option value="{{$x}}">{{ $x }}</option>
                                @endfor
                            @elseif($is_non_leap)
                                @for($x = 1; $x <= 28; $x ++)
                                    <option value="{{$x}}">{{ $x }}</option>
                                @endfor
                            @else
                                @for($x = 1; $x <= 31; $x ++)
                                    <option value="{{$x}}">{{ $x }}</option>
                                @endfor
                            @endif
                        </select>
                        @error('day')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Save</button>
            </div>
        </div>
    </div>
</div>
