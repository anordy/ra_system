<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label class="control-label">Weaver Report</label>
                        <input type="file" class="form-control  @error('weaverReport') is-invalid @enderror"
                            wire:model.lazy="weaverReport">
                        @error('weaverReport')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        <label>Finalizing Assesment</label>
                        <select class="form-control" wire:model.lazy="natureOfAttachment">
                            <option value="">Select Attachment</option>
                            <option>Notice</option>
                            <option>Setting</option>
                        </select>
                        @error('natureOfPossession')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group col-lg-4">
                        @if ($natureOfAttachment === 'Notice')
                            <label class="control-label">Notice Confirmed</label>
                            <input type="file" class="form-control  @error('noticeReport') is-invalid @enderror"
                                wire:model.lazy="noticeReport">
                            @error('noticeReport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                        @if ($natureOfAttachment === 'Setting')
                            <label class="control-label">Setting Out Ammend</label>
                            <input type="file" class="form-control  @error('settingReport') is-invalid @enderror"
                                wire:model.lazy="settingReport">
                            @error('settingReport')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
