<div class="row">

    <div class="col-md-4 form-group">
        <label>Module</label>
        <select wire:model="module" class="form-control @error('module') is-invalid @enderror">
            <option value="">Select module</option>
            @foreach ($sub_sys_modules as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('module')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="col-md-4 form-group">
        <label>Approval Level</label>
        <select wire:model="level" class="form-control @error('level') is-invalid @enderror">
            <option value="">Select approval level</option>
            @foreach ($levels as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('level')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="col-md-4 form-group">
        <label>Role</label>
        <select wire:model="role" class="form-control @error('role') is-invalid @enderror">
            <option value="">Select role</option>
            @foreach ($roles as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('role')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="col-md-12 form-group d-flex justify-content-end">
        <button  class="btn btn-primary rounded-0" wire:click="submit" wire:loading.attr="disable">
            <i class="bi bi-arrow-return-right mr-2" wire:loading.remove wire:target="submit"></i>
            <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading
               wire:target="submit"></i>
            Submit
        </button>
    </div>

</div>
