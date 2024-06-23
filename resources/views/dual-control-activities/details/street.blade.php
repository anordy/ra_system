<div class="card">
    <div class="card-header font-weight-bold text-uppercase">Street Details</div>
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
                    <th>Ward</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->ward->name ?? 'N/A' : getWard($old_values->ward_id) ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ getWard($new_values->ward_id) ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->ward->name ?? 'N/A' : getWard($old_values->ward_id) ?? 'N/A',
                            $new_values->district_id ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>District</th>
                    <td>{{ $data->ward->district->name }}</td>
                    <td></td>
                    @if($result->action == \App\Models\DualControl::EDIT)
                        <td class="table-success">NOT CHANGED</td>
                    @endif
                </tr>

                <tr>
                    <th>Region</th>
                    <td>{{ $data->ward->district->region->name }}</td>
                    <td></td>
                    @if($result->action == \App\Models\DualControl::EDIT)
                        <td class="table-success">NOT CHANGED</td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>