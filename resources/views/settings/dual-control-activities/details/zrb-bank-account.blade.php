<div>

    {{-- <div class="card">
        <div class="card-header">Old Values</div>
        <div class="card-body">
            <div class="row m-2 pt-3">
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Bank</span>
                    <p class="my-1">{{ $result->action != \App\Models\DualControl::EDIT ? $data : $old_values }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Account Number</span>
                    <p class="my-1">
                        {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Account Name</span>
                    <p class="my-1">
                        {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_name : $old_values->account_name }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Branch Name</span>
                    <p class="my-1">
                        {{ $result->action != \App\Models\DualControl::EDIT ? $data->branch_name : $old_values->branch_name }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">SWIFT Code</span>
                    <p class="my-1">
                        {{ $result->action != \App\Models\DualControl::EDIT ? $data->swift_code : $old_values->swift_code }}
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="font-weight-bold text-uppercase">Currency</span>
                    <p class="my-1">
                        {{ $result->action != \App\Models\DualControl::EDIT ? $data->currency->id : $old_values->currency->id }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if ($new_values)
        <div class="card">
            <div class="card-header">New Values</div>
            <div class="card-body">
                <div class="row m-2 pt-3">
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Bank</span>
                        <p class="my-1">{{ \App\Models\Bank::find($new_values->bank_id)->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Account Number</span>
                        <p class="my-1">{{ $new_values->account_number }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Account Name</span>
                        <p class="my-1">{{ $new_values->account_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Branch Name</span>
                        <p class="my-1">{{ $new_values->branch_name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">SWIFT Code</span>
                        <p class="my-1">{{ $new_values->swift_code }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <span class="font-weight-bold text-uppercase">Account Name</span>
                        <p class="my-1">{{ \App\Models\Currency::find($new_values->currency_id)->iso }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}
    <div class="card">
        <div class="card-header">New Values</div>
        <div class="card-body">
            <table class="table table-striped table-sm">
                <label class="font-weight-bold text-uppercase">BPRA Data Verification</label>
                <thead>
                    <th style="width: 18%">Property</th>
                    <th style="width: 37%">Old Data</th>
                    <th style="width: 35%">New Data</th>
                    <th style="width: 10%">Status</th>
                </thead>
                <tbody>
                    <tr>
                        <th>Bank Name</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->bank->name : \App\Models\Bank::find($old_values->bank_id)->name }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>Account Name</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_name : $old_values->account_name }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>Account Number</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>Account Number</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->account_number : $old_values->account_number }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>Branch Name</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->branch_name : $old_values->branch_name }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>SWIFT Code</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->swift_code : $old_values->swift_code }}
                            </p>
                        </td>
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
                    </tr>
                    <tr>
                        <th>Currency</th>
                        <td>
                            <p class="my-1">
                                {{ $result->action != \App\Models\DualControl::EDIT ? $data->currency->iso : \App\Models\Currency::find($old_values->currency_id)->iso }}
                            </p>
                        </td>
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
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
