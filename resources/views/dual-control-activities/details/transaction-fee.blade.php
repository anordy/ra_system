<div>

    <div class="card">
        <div class="card-header">
            Transaction fee Details
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
                    <th>Minimum Amount</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $result->minimum_amount : $old_values->minimum_amount }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->minimum_amount ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->minimum_amount : $old_values->minimum_amount, $new_values->minimum_amount))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Maximum Amount</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $result->maximum_amount : $old_values->maximum_amount }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->maximum_amount ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->maximum_amount : $old_values->maximum_amount, $new_values->maximum_amount))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Fee</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $result->fee : $old_values->fee }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->fee ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->fee : $old_values->fee, $new_values->fee))
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