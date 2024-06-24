<div class="card">
    <div class="card-header font-weight-bold text-uppercase">Country Details</div>
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
                            {{ $new_values->name ?? 'N/A' }}
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
                    <th>Code</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->code ?? 'N/A' : $old_values->code ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->code ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->code ?? 'N/A' : $old_values->code ?? 'N/A',
                            $new_values->code ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Nationality</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->nationality ?? 'N/A' : $old_values->nationality ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->nationality ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->nationality ?? 'N/A' : $old_values->nationality ?? 'N/A',
                            $new_values->nationality ?? 'N/A'))
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