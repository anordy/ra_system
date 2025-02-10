<div>
    <div class="card rounded-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 pt-3">
                    <h4>Select License Type Initiation</h4>
                    <p class="mb-3">Provide the required applicant information to continue</p>
                    <hr/>
                </div>
                <div class="form-group col-lg-4">
                    <div class="form-group">
                        <label>Application Type *</label>
                        <select class="form-control @error('type') is-invalid @enderror"
                                wire:model="type" required>
                            <option>Select</option>
                            <option value="{{ \App\Enum\DlFeeType::FRESH }}">Fresh Application</option>
                            <option value="{{ \App\Enum\DlFeeType::ADD_CLASS }}">Add Class</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($type === \App\Enum\DlFeeType::FRESH)
        <livewire:drivers-license.initiation.fresh-application/>
    @elseif($type === \App\Enum\DlFeeType::ADD_CLASS)
        <livewire:drivers-license.initiation.add-class/>
    @endif

</div>