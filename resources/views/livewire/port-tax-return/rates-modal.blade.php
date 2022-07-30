<div>
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase text-center">Adding rates configuration Port Tax return</h6>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                            class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label class="control-label">Port Tax Services</label>
                            <select wire:model.lazy="service_code" name="service_code" class="form-control">
                                <option  value="">select service</option>
                                @foreach($services as $service)
                                    <option value="{{$service->code}}">{{$service->name}}</option>
                                @endforeach
                            </select>
                            @error('service_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Port Tax Category</label>
                            <select wire:model.lazy="cat_code" name="cat_code" class="form-control">
                                <option  value="">select category</option>
                                @if(!empty($service_code))
                                    @foreach($categories as $category)
                                        <option value="{{$category->code}}">{{$category->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('cat_code')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-lg-6">
                            <label class="control-label">Rate</label>
                            <input type="text" class="form-control" wire:model.lazy="rate" id="rate">
                            @error('rate')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit()'>Save</button>
            </div>
        </div>
    </div>
</div>
