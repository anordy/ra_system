<div>

    <div class="card">
        <div class="card-header">
            @if($result->action == \App\Models\DualControl::ADD)
                Added Values
            @elseif($result->action == \App\Models\DualControl::EDIT)
                Old Values
            @else
                Values
            @endif
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->name : $old_values->name }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">GFS Code</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->gfs_code : $old_values->gfs_code}}</p>
                </div>

            </div>
        </div>
    </div>

    @if($new_values)
        <div class="card">
            <div class="card-header">New Values</div>
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Name</span>
                        @if(!compareEditedValues($old_values->name,$new_values->name ))
                            <p class="my-1 text-danger">{{ $new_values->name }}</p>
                        @else
                            <p class="my-1">{{ $new_values->name }}</p>
                        @endif
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Report To</span>
                        @if(!compareEditedValues($old_values->gfs_code,$new_values->gfs_code ))
                            <p class="my-1 text-danger">{{ $new_values->gfs_code }}</p>
                        @else
                            <p class="my-1">{{ $new_values->gfs_code }}</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>