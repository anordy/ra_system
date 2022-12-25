<div>

    <div class="card">
        <div class="card-header">
            @if($result->action == \App\Models\DualControl::ADD)
                Added Values
            @else
                Old Values
            @endif
        </div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Name</span>
                    <p class="my-1">{{ $result->action == \App\Models\DualControl::ADD ? $data->name : $old_values->name }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Report To</span>
                    <p class="my-1">{{ $report_to_old }}</p>
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
                        <p class="my-1">{{ $new_values->name }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Report To</span>
                        <p class="my-1">{{ $report_to_new }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>