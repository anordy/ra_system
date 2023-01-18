<div class="card">
    <div class="card-header font-weight-bold text-uppercase">API User Details</div>
    <div class="card-body">
        <table class="table table-striped border">
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