<div>

    <div class="card">
        <div class="card-header">Old Values</div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->name : $old_values->name }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Description</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->description : $old_values->description }}</p>
                </div>

            </div>
        </div>
    </div>

    @if($new_values)
        <div class="card">
            <div class="card-header">New Values</div>
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Name</span>
                        <p class="my-1">{{ $new_values->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Description</span>
                        <p class="my-1">{{ $new_values->description }}</p>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>