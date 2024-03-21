<div class="card">
    <div class="card-header font-weight-bold text-uppercase">System Setting Details</div>
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
                    <th>Setting Category</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->system_setting_category->name : \App\Models\SystemSetting::find($old_values->system_setting_category_id)->name }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\SystemSetting::find($new_values->system_setting_category_id)->name ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT
                                ? $data->system_setting_category->name
                                : \App\Models\SystemSetting::find($old_values->system_setting_category_id)->name,
                            \App\Models\SystemSetting::find($new_values->system_setting_category_id)->name ?? ''))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
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
                    <th>Code</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->code : $old_values->code }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->code }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->code : $old_values->code,
                            $new_values->code))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Value</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->value : $old_values->value }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->value }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->value : $old_values->value,
                            $new_values->value))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Unit</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->unit : $old_values->unit }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->unit }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->unit : $old_values->unit,
                            $new_values->unit))
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