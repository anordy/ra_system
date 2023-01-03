<div>

    <div class="card">
        <div class="card-header">
            Role Details
        </div>

        <div class="card-body">
            <table class="table table-striped table-sm table-bordered">
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
                    <th>Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? ($data->name ?? '') : ($old_values->name ?? '') }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->name ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? ($data->name ?? 'Not Set') : ($old_values->name ?? ''), ($new_values->name ?? '')))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Report To</th>
                    <td>
                        <p class="my-1">
                            {{ $report_to_old }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $report_to_new ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $report_to_old, $report_to_new))
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