<div>
    <div class="card">
        <div class="card-header">
           Seven Days Financial Month Detail
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
                    <th>Year</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->year->code : \App\Models\FinancialYear::query()->select('code')->findOrFail($old_values->financial_year_id)->code }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\FinancialYear::query()->select('code')->findOrFail($new_values->financial_year_id)->code }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->financial_year_id : $old_values->financial_year_id, $new_values->financial_year_id))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Month</th>
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
                    <th>Due Date</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->due_date : $old_values->due_date }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->due_date ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->due_date : $old_values->due_date, $new_values->due_date))
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