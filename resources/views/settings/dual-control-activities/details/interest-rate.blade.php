<div>

    <div class="card">
        <div class="card-header">
            Interest Rate Details
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
                    <th>Year</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->year : $old_values->year }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->name ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->year : $old_values->year, $new_values->year))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Rate</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->rate : $old_values->rate }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->rate ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->rate : $old_values->rate, $new_values->rate))
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