<div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">{{$instance?'Update':'Add'}} {{$setting_title}}</h5>
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><i
                        class="fa fa-times-circle"></i></button>
            </div>
            <div class="modal-body">
                <div class="row pr-3 pl-3">
                    <div class="form-group col-lg-12">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control" wire:model.lazy="name" id="name">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @foreach($relation_options as $field=>$relation)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label">{{$relation['title']}}</label>
                            <select wire:model="relation_data.{{$field}}" class="form-control" id="{{$field}}">
                                <option>Choose option</option>
                                @foreach ($relation['data'] as $option)
                                    <option value="{{ $option['id'] }}">{{ $option['name']?? $option['type'] }}</option>
                                @endforeach
                            </select>
                            @error("relation_data.{$field}")
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach

                @foreach($field_options as $f=>$options)
                    <div class="row pr-3 pl-3">
                        <div class="form-group col-lg-12">
                            <label class="control-label" for="{{$f}}">{{$options['title']}}</label>
                            <input type="text" class="form-control" wire:model.lazy="data.{{$f}}" id="{{$f}}">
                            @error("data.{$f}")
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" wire:click='submit'>Save changes</button>
            </div>
        </div>
    </div>
</div>
