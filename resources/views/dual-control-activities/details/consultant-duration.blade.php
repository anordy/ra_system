<div>

    <div class="card">
        <div class="card-header">
            Tax Consultant Fee Details
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
                    <th>Category</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->category : $old_values->category }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->category ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->category : $old_values->category, $new_values->category))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Duration</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->duration : $old_values->duration }} years
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->duration ?? '' }} years
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->duration : $old_values->duration, $new_values->duration))
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