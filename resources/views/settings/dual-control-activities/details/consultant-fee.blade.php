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
                    <span class="font-weight-bold text-uppercase">Category</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->category : $old_values->category }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Duration</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->duration : $old_values->duration }} Years</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Amount</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? number_format($data->amount ): number_format($old_values->amount) }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->currency : $old_values->currency }}</p>
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
                        <span class="font-weight-bold text-uppercase">Category</span>
                        <p class="my-1">{{  $new_values->category }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Duration</span>
                        <p class="my-1">{{  $new_values->duration }} Years</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Amount</span>
                        <p class="my-1">{{ number_format($new_values->amount) }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Currency</span>
                        <p class="my-1">{{  $new_values->currency }} </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>