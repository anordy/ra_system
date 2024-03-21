<div>

    <div class="card">
        <div class="card-header">
            Penalty Rate Details
        </div>

        <div class="card-body">
            <table class="table table-striped table-sm table-bordered">
                <thead>
                <th>Property</th>
                @if ($new_values)
                    <th>Old Data</th>
                    <th>New Data</th>
                    <th>Status</th>
                @else
                    <th>Data</th>
                @endif
                </thead>
                <tbody>
                <tr>
                    <th>Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->name : $old_values->name }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->name ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->name : $old_values->name, $new_values->name))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Code</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->code : $old_values->code }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->code ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->code : $old_values->code, $new_values->code))
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $result->rate : $old_values->rate }}
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

                <tr>
                    <th>Year</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $result->year : $old_values->year }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->year ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->year : $old_values->year, $new_values->year))
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