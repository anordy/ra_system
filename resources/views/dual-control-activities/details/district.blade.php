<div class="card">
    <div class="card-header font-weight-bold text-uppercase">District Details</div>
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->name ?? 'N/A' : $old_values->name ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->name  ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->name ?? 'N/A' : $old_values->name ?? 'N/A',
                            $new_values->name ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>District</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->region->name ?? 'N/A' : getRegion($old_values->region_id) ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ getRegion($new_values->region_id) ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->region->name ?? 'N/A' : getRegion($old_values->region_id) ?? 'N/A',
                            $new_values->location ?? 'N/A'))
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