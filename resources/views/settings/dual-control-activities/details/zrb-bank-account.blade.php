<div class="card">
    <div class="card-header font-weight-bold text-uppercase">Zrb Bank Account Details</div>
    <div class="card-body">
        <table class="table table-striped table-sm">
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
                    <th>Bank Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->bank->name : \App\Models\Bank::find($old_values->bank_id)->name }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\Bank::find($new_values->bank_id)->name ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT
                                ? $data->bank->name
                                : \App\Models\Bank::find($old_values->bank_id)->name,
                            \App\Models\Bank::find($new_values->bank_id)->name ?? ''))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Account Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_name : $old_values->account_name }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->account_name }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->account_name : $old_values->account_name,
                            $new_values->account_name))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Account Number</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->account_number }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number,
                            $new_values->account_number))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Account Number</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->account_number }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number,
                            $new_values->account_number))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Branch Name</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->branch_name : $old_values->branch_name }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->branch_name }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->branch_name : $old_values->branch_name,
                            $new_values->branch_name))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>SWIFT Code</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->swift_code : $old_values->swift_code }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->swift_code }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->swift_code : $old_values->swift_code,
                            $new_values->swift_code))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Currency</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->currency->iso : \App\Models\Currency::find($old_values->currency_id)->iso }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\Currency::find($new_values->currency_id)->iso ?? '' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT
                                ? $data->currency->iso
                                : \App\Models\Currency::find($old_values->currency_id)->iso,
                            \App\Models\Currency::find($new_values->currency_id)->iso ?? ''))
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
