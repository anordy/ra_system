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
                    <p class="my-1">{{ $data->year->code  }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Month</span>
                    <p class="my-1">{{ $data->name }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Due Date</span>
                    <p class="my-1">{{ $data->due_date }}</p>
                </div>

            </div>
        </div>
    </div>
</div>