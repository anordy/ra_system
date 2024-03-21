<div class="card">
    <div class="card-header font-weight-bold text-uppercase">API User Details</div>
    <div class="card-body">
        <table class="table table-striped border">
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
                    <th>App Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->app_name ?? 'N/A' : $old_values->app_name ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->app_name  ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->app_name ?? 'N/A' : $old_values->app_name ?? 'N/A',
                            $new_values->app_name ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>App Url</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->app_url ?? 'N/A' : $old_values->app_url ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->app_url ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->app_url ?? 'N/A' : $old_values->app_url ?? 'N/A',
                            $new_values->app_url ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Username</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->username ?? 'N/A' : $old_values->username ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->username ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->username ?? 'N/A' : $old_values->username ?? 'N/A',
                            $new_values->username ?? 'N/A'))
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