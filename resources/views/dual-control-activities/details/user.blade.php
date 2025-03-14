<div>

    <div class="card">
        <div class="card-header">
            User Details
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
                    <th>First Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->fname : $old_values->fname }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->fname ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->fname : $old_values->fname, $new_values->fname))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Last Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->lname : $old_values->lname }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->lname ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->lname : $old_values->lname, $new_values->lname))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Phone Number</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->phone : $old_values->phone }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->phone ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->phone : $old_values->phone, $new_values->phone))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Email</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->email : $old_values->email }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->email ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->email : $old_values->email, $new_values->email))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Gender</th>
                    <td>
                        <p class="my-1">
                            @if(!empty($result->action != \App\Models\DualControl::EDIT))
                                @if ($data->gender == 'M')
                                    <span>Male</span>
                                @else
                                    <span>Female</span>
                                @endif
                            @else
                                @if ($old_values->gender == 'M')
                                    <span>Male</span>
                                @else
                                    <span>Female</span>
                                @endif
                            @endif
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            @if ($new_values->gender == 'M')
                                <span>Male</span>
                            @else
                                <span>Female</span>
                            @endif
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->gender : $old_values->gender, $new_values->gender))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                <tr>
                    <th>Override OTP</th>
                    <td>
                        <p class="my-1">
                            @if(!empty($result->action != \App\Models\DualControl::EDIT))
                                @if ($data->override_otp)
                                    <span>Allowed</span>
                                @else
                                    <span>Restricted</span>
                                @endif
                            @else
                                @if ($old_values->override_otp)
                                    <span>Allowed</span>
                                @else
                                    <span>Restricted</span>
                                @endif
                            @endif
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            @if ($new_values->override_otp)
                                <span>Allowed</span>
                            @else
                                <span>Restricted</span>
                            @endif
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->override_otp : $old_values->override_otp, $new_values->override_otp))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>

                @if($result->action == \App\Models\DualControl::DEACTIVATE || $result->action == \App\Models\DualControl::ACTIVATE)
                    <tr>
                        <th>Status</th>
                        <td>
                            <p class="my-1">
                                @if(!empty($result->action != \App\Models\DualControl::EDIT))
                                    @if ($data->status == 1)
                                        <span>Active</span>
                                    @else
                                        <span>Inactive</span>
                                    @endif
                                @else
                                    @if ($old_values->status == 1)
                                        <span>Active</span>
                                    @else
                                        <span>Inactive</span>
                                    @endif
                                @endif
                            </p>
                        </td>
                        @if ($new_values)
                            <td>
                                @if ($new_values->status == 1)
                                    <span>Active</span>
                                @else
                                    <span>Inactive</span>
                                @endif
                            </td>

                            @if (compareDualControlValues(
                                $result->action != \App\Models\DualControl::EDIT ? $data->status : $old_values->status, $new_values->status))
                                <td class="table-success">NOT CHANGED</td>
                            @else
                                <td class="table-danger">CHANGED</td>
                            @endif
                        @endif
                    </tr>
                @endif


                <tr>
                    <th>Department</th>
                    <td>
                        <p class="my-1">
                            @if(!empty($result->action != \App\Models\DualControl::EDIT))
                                {{ getDepartment($data->department_id) }}
                            @else
                                {{ getDepartment($old_values->department_id) }}
                            @endif
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ getDepartment($new_values->department_id) }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->department_id : $old_values->department_id, $new_values->department_id))
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