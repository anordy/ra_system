<div class="row">

    <div class="form-group col-lg-6">
        <label class="control-label">Financial year</label>
        <select wire:model.lazy="year" name="year" class="form-control">
            <option  value="">select year</option>
            @foreach($years as $row)
                <option value="{{$row->id}}">{{$row->name}}</option>
            @endforeach
        </select>
        @error('year')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="control-label">Return month</label>
        <select wire:model.lazy="month" name="month" class="form-control">
            <option value="">--select month--</option>
            @if(!empty($year))
            @foreach($months as $row)
                <option style="text-transform: capitalize;" value="{{$row->code}}">{{$row->name}}</option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="col-md-12 text-center">
        <div class="d-flex justify-content-end">
            <button wire:click="continue()" class="btn btn-info px-5 mt-3" type="button" >
                Continue
            </button>
            <div wire:loading.delay wire:target="submit()">
                Processing Registration...
            </div>
        </div>
    </div>
</div>