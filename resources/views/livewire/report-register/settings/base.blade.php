<div class="card">
    <div class="card-header">
        <h5 class="text-uppercase">General Report Settings</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label class="control-label">Days To Breach *</label>
                <input type="text" class="form-control" wire:model="daysToBreach">
                <small>The number of days when an incident is breached if not resolved</small>
                @error('daysToBreach')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-12 text-right">
                <button class="btn btn-primary" wire:click="submit">
                    <i class="mr-2" wire:loading.remove wire:target="submit"></i>
                    <i class="spinner-border spinner-border-sm mr-2" role="status" wire:loading wire:target="submit"></i>
                    Update Settings
                </button>
            </div>
        </div>
    </div>
</div>

