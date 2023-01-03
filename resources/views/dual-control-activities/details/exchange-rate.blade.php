<div>

    <div class="card">
        <div class="card-header">
            Exchange Rate Detail
        </div>
        <div class="card-body">
            <table class="table table-striped table-sm table-bordered">
                <thead>
                <th style="width: 18%">Property</th>
                @if ($new_values)
                    <th style="width: 37%">Old Data</th>
                    <th style="width: 35%">New Data</th>
                    <th style="width: 10%">Status</th>
                @else
                    <th style="width: 82%">Data</th>
                @endif
                </thead>
                <tbody>
                <tr>
                    <th>Mean</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->mean : $old_values->mean }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->mean ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->mean : $old_values->mean, $new_values->mean))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Spot Buying</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->spot_buying : $old_values->spot_buying }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->spot_buying ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->spot_buying : $old_values->spot_buying, $new_values->spot_buying))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Spot Selling</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->spot_selling : $old_values->spot_selling }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->spot_selling ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->spot_selling : $old_values->spot_selling, $new_values->spot_selling))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Exchange Date</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->exchange_date : $old_values->exchange_date }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->exchange_date ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->exchange_date : $old_values->exchange_date, $new_values->exchange_date))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                </tbody>
            </table>
        </div>

    </div>

</div>