<div class="card">
    <div class="card-body">
        <div class="row px-3">
            <div class="form-group col-lg-12">
                <label class="control-label h6 text-uppercase">Auditors</label>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Team Leader</label>
                <input type="text" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Team Member</label>
                <input type="text" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row px-3">
            <div class="form-group col-lg-12">
                <label class="control-label h6 text-uppercase">Notice of Asessement</label>
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Principal Amount</label>
                <input type="text" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Interest Amount</label>
                <input type="text" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-lg-6">
                <label class="control-label">Penalty Amount</label>
                <input type="text" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group col-lg-6">
                <label class="control-label">Assessment Report</label>
                <input type="file" class="form-control" wire:model.lazy="name" id="name">
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>
</div>
