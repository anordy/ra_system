<div class="col-md-6 ">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Select ISIIC I</label>
        <select class="form-control @error('isiic_i') is-invalid @enderror"
            wire:change="isiiciChange($event.target.value)" wire:model="isiic_i">
            <option value="null" disabled selected>Select</option>
            @foreach ($isiiciList as $row)
                <option value="{{ $row->id }}">{{ $row->description }}</option>
            @endforeach
        </select>
        @error('isiic_i')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="col-md-6 ">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Select ISIIC II</label>
        <select class="form-control @error('isiic_ii') is-invalid @enderror"
            wire:change="isiiciiChange($event.target.value)" wire:model="isiic_ii">
            <option value='null' disabled selected>Select</option>
            @foreach ($isiiciiList as $row)
                <option value="{{ $row->id }}">{{ $row->description }}</option>
            @endforeach
        </select>
        @error('isiic_ii')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="col-md-6 ">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Select ISIIC III</label>
        <select class="form-control @error('isiic_iii') is-invalid @enderror"
            wire:change="isiiciiiChange($event.target.value)" wire:model="isiic_iii">
            <option value="null" disabled selected>Select</option>
            @foreach ($isiiciiiList as $row)
                <option value="{{ $row->id }}">{{ $row->description }}</option>
            @endforeach
        </select>
        @error('isiic_iii')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="col-md-6 ">
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Select ISIIC IV</label>
        <select class="form-control @error('isiic_iv') is-invalid @enderror" wire:model="isiic_iv">
            <option value="null" disabled selected>Select</option>
            @foreach ($isiicivList as $row)
                <option value="{{ $row->id }}">{{ $row->description }}</option>
            @endforeach
        </select>
        @error('isiic_iv')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label>Tax Types</label>
        <select class="form-control @error('selectedTaxTypes') is-invalid @enderror" multiple style="min-height: 200px;"
            wire:model="selectedTaxTypes">
            @foreach ($taxTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
        @error('selectedTaxTypes')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
