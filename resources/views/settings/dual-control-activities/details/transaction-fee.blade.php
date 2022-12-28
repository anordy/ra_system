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
                    <span class="font-weight-bold text-uppercase">Minimum Amount</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->minimum_amount : $old_values->minimum_amount }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Maximum Amount</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->maximum_amount : $old_values->maximum_amount }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Fee</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->fee : $old_values->fee }}</p>
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
                        <span class="font-weight-bold text-uppercase">Minimum Amount</span>
                        <p class="my-1">{{ $new_values->minimum_amount }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Maximum Amount</span>
                        <p class="my-1">{{ $new_values->maximum_amount }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Fee</span>
                        <p class="my-1">{{ $new_values->fee }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>