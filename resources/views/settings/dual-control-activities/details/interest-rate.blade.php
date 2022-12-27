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
                    <span class="font-weight-bold text-uppercase">Year</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->year : $old_values->year }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Rate</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->rate : $old_values->rate }}</p>
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
                        <span class="font-weight-bold text-uppercase">Year</span>
                        <p class="my-1">{{ $new_values->year }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Rate</span>
                        <p class="my-1">{{ $new_values->rate }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>