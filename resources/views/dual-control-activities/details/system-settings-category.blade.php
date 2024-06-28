<div class="card">
    <div class="card-header font-weight-bold text-uppercase">System Setting Category Details</div>
    <div class="card-body">
        <table class="table table-striped table-sm">
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
                            {{ $new_values->name }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->name : $old_values->name,
                            $new_values->name))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->description : $old_values->description }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->description }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->description : $old_values->description,
                            $new_values->description))
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