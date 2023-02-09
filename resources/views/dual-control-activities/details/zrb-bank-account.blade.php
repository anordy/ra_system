<div class="card">
    <div class="card-header font-weight-bold text-uppercase">ZRA Bank Account Details</div>
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->bank->name ?? 'N/A' : \App\Models\Bank::find($old_values->bank_id)->name ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\Bank::find($new_values->bank_id)->name ?? 'N/A' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT
                                ? $data->bank->name
                                : \App\Models\Bank::find($old_values->bank_id)->name ?? 'N/A',
                            \App\Models\Bank::find($new_values->bank_id)->name ?? 'N/A'))
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_name ?? 'N/A' : $old_values->account_name ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->account_name ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->account_name ?? 'N/A' : $old_values->account_name ?? 'N/A',
                            $new_values->account_name ?? 'N/A'))
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number ?? 'N/A' : $old_values->account_number ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->account_number ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->account_number ?? 'N/A' : $old_values->account_number ?? 'N/A',
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->branch_name ?? 'N/A' : $old_values->branch_name ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->branch_name ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->branch_name ?? 'N/A' : $old_values->branch_name ?? 'N/A',
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->swift_code ?? 'N/A' : $old_values->swift_code ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ $new_values->swift_code ?? 'N/A' }}
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->swift_code ?? 'N/A' : $old_values->swift_code ?? 'N/A',
                            $new_values->swift_code ?? 'N/A'))
                            <td class="table-success">NOT CHANGED</td>
                        @else
                            <td class="table-danger">CHANGED</td>
                        @endif
                    @endif
                </tr>
                <tr>
                    <th>Account Type</th>
                    <td>
                        <p class="my-1">
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->is_tranfer ?? 'N/A' : $old_values->is_tranfer ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            @if($new_values->is_transfer == true)
                                {{ \App\Models\ZrbBankAccount::TRANSFER_ACCOUNT }}
                            @elseif($new_values->is_transfer == false)
                                {{ \App\Models\ZrbBankAccount::NORMAL_ACCOUNT }}
                            @endif
                        </td>
                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT ? $data->is_tranfer ?? 'N/A' : $old_values->is_transfer ?? 'N/A',
                            $new_values->is_tranfer ?? 'N/A'))
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
                            {{ $result->action != \App\Models\DualControl::EDIT ? $data->currency_iso ?? 'N/A' : $old_values->currency_iso ?? 'N/A' }}
                        </p>
                    </td>
                    @if ($new_values)
                        <td>
                            {{ \App\Models\Currency::find($new_values->currency_id)->iso ?? 'N/A' }}
                        </td>

                        @if (compareDualControlValues(
                            $result->action != \App\Models\DualControl::EDIT
                                ? $data->currency->iso
                                : $old_values->currency_iso,
                            \App\Models\Currency::find($new_values->currency_id)->iso ?? 'N/A'))
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
