<div>

    <div class="card">
        <div class="card-header">
            Tax Types Details
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
                    <th>GFS Code</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->gfs_code : $old_values->gfs_code }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->gfs_code ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->gfs_code : $old_values->gfs_code, $new_values->gfs_code))
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