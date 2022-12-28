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
                    <span class="font-weight-bold text-uppercase">Mean</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->mean : $old_values->mean }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Spot Buying</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->spot_buying : $old_values->spot_buying }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Spot Selling</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->spot_selling : $old_values->spot_selling }}</p>
                </div>

                <div class="col-md-3 mb-3">
                    <span class="font-weight-bold text-uppercase">Exchange Date</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data->exchange_date : $old_values->exchange_date }}</p>
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
                        <span class="font-weight-bold text-uppercase">Mean</span>
                        <p class="my-1">{{ $new_values->mean }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Spot Buying</span>
                        <p class="my-1">{{ $new_values->spot_buying }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Spot Selling</span>
                        <p class="my-1">{{ $new_values->spot_selling }}</p>
                    </div>

                    <div class="col-md-3 mb-3">
                        <span class="font-weight-bold text-uppercase">Exchange Date</span>
                        <p class="my-1">{{ $new_values->exchange_date }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>