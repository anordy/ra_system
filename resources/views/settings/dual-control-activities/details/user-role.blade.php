<div>
    <div class="card">
        <div class="card-header">
            User Role Detail
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
                        <th>Role</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? getRole($result->role_id): getRole($old_values->role_id) }}
                            </p>
                        </td>
                        @if ($new_values)
                            <td>
                                {{ getRole($new_values->role_id) ?? '' }}
                            </td>

                            @if (compareDualControlValues(
                                $result->action != \App\Models\DualControl::EDIT ? getRole($result->role_id): getRole($old_values->role_id),
                                getRole($new_values->role_id)))
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
