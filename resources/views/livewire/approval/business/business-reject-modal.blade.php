<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase align-items-center">Reject Business Registration</h6>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Section Requiring Correction</label>
                        <select type="text" class="form-control" wire:model.defer="correctionType" id="correctionType">
                            <option value="none">N/A</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::INFORMATION }}">Business Information</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::LOCATION }}">Business Location</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::HOTEL }}">Hotel Information</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::CONTACT }}">Responsible Person</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::BANK }}">Bank A/C Information</option>
                            <option value="{{ \App\Enum\BusinessCorrectionType::ATTACHMENTS }}">Business Attachments</option>
                        </select>
                        @error('correctionType')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-lg-12">
                        <label class="control-label" for="comments">Comments</label>
                        <textarea rows="6" type="text" class="form-control" wire:model.defer="comments" id="comments"></textarea>
                        @error('comments')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" wire:click='submit' wire:loading.attr="disabled">
                    <div wire:loading.delay wire:target="submit">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Filled Incorrect Return for Correction</button>
            </div>
        </div>
    </div>
</div>
