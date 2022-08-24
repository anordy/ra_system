<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">Registration Change Request</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Requested Registration Type</label>
                        <select class="form-control" wire:model.lazy="registration_type_id" id="registration_type_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrRegistrationType::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('registration_type_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                @if(\App\Models\MvrRegistrationType::query()->whereIn('name',[
                    \App\Models\MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED,
                    \App\Models\MvrRegistrationType::TYPE_DIPLOMATIC
                    ])->where(['id'=>$registration_type_id])->exists() || (\App\Models\MvrRegistrationType::query()->find($registration_type_id)->external_defined ?? null)==1)
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Plate Number</label>
                        <input type="text" class="form-control" wire:model.lazy="custom_plate_number" id="custom_plate_number">
                        @error('custom_plate_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @elseif(\App\Models\MvrRegistrationType::query()->whereIn('name',[\App\Models\MvrRegistrationType::TYPE_PRIVATE_GOLDEN])->where(['id'=>$registration_type_id])->exists())
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">Golden Plate Number</label>
                            <select class="form-control" wire:model.lazy="custom_plate_number" id="custom_plate_number">
                                <option value="" selected>Choose option</option>
                                @foreach (\App\Models\MvrMotorVehicleRegistration::getGoldenPlateNumbers() as $row)
                                    <option value="{{$row}}">{{ $row}}</option>
                                @endforeach
                            </select>
                            @error('custom_plate_number')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Plate Number Size</label>
                        <select class="form-control" wire:model.lazy="plate_number_size_id" id="plate_number_size_id">
                            <option value="" selected>Choose option</option>
                            @foreach (\App\Models\MvrPlateSize::query()->get() as $row)
                                <option value="{{$row->id}}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                        @error('plate_number_size_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Agent Z-Number</label>
                        <input type="text" class="form-control" wire:model.lazy="agent_z_number" id="agent_z_number">
                        @error('agent_z_number')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <button wire:click="agentLookup" wire:loading.attr="disabled" class="btn btn-primary">
                                    <div wire:loading wire:target="submit">
                                        <div class="spinner-border mr-1 spinner-border-sm text-light">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    Lookup
                                </button>
                            </div>
                            <div class="col-6">
                                <span class="p-1">
                                    <strong>Agent Name: </strong><span class="text-center">{{$agent_name??'Not available'}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Submit</button>
            </div>
        </div>
    </div>
</div>
