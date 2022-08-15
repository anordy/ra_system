<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        Penalty
                                    </td>
                                    <td colspan="2" class="text-center">
                                        Interest
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Penalty Percentage (%)
                                    </td>
                                    <td>
                                        <div class="input-group @error($penaltyPercent) is-invalid @enderror">
                                            <input class="form-control @error($penaltyPercent) is-invalid @enderror"
                                                wire:model="penaltyPercent" type="number" min=0 max=100
                                                {{-- wire:change="calculatePenalty()" --}} />
                                            @error($penaltyPercent)
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </td>
                                    <td>
                                        Interest Percent (%)
                                    </td>
                                    <td>
                                        <div class="input-group @error($interestPercent) is-invalid @enderror">
                                            <input class="form-control @error($interestPercent) is-invalid @enderror"
                                                wire:model="interestPercent" type="number" min=0 max=50
                                                {{-- wire:change="calculatePenalty()" --}} />
                                            @error($interestPercent)
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Penalty Amount
                                    </td>

                                    <td>
                                        {{-- {{ $this->objection->taxVerificationAssesment->penalty_amount }} --}}
                                    </td>
                                    <td>
                                        Interest Amount
                                    </td>
                                    <td>
                                        {{-- {{ $this->objection->taxVerificationAssesment->interest_amount }} --}}
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        Waived Penalty Amount
                                    </td>
                                    <td>
                                        {{ $penaltyAmount ?? 'N/A' }}
                                    </td>
                                    <td>
                                        Waived Interest Amount
                                    </td>
                                    <td>
                                        {{ $interestAmount ?? 'N/A' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Due Penalty Amount
                                    </td>
                                    <td>
                                        {{-- {{ $this->objection->taxVerificationAssesment->penalty_amount - $penaltyAmount }} --}}
                                    </td>
                                    <td>
                                        Due Interest Amount
                                    </td>
                                    <td>
                                        {{-- {{ $this->objection->taxVerificationAssesment->interest_amount - $interestAmount }} --}}
                                    </td>

                                </tr>


{{--                                <tr>--}}
{{--                                    <td class="card-footer text-center">--}}
{{--                                        Total--}}
{{--                                    </td>--}}
{{--                                    <td colspan="4" class="font-weight-bold text-center">--}}
{{--                                        {{ number_format($total) }}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                                <tr>
                                    <td class="card-footer text-center">
                                        Principal Amount
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{-- {{ number_format($objection->taxVerificationAssesment->principal_amount) }} --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="card-footer text-center">
                                        Tax Deposited
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{-- {{ number_format($objection->tax_not_in_dispute) }} --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="card-footer text-center">
                                        Total Amount Due
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{-- {{ number_format($total) }} --}}
                                    </td>
                                </tr>
                            </tbody>
{{--                            <tfoot>--}}
{{--                                <tr>--}}
{{--                                    <td class="card-footer text-center">--}}
{{--                                        Total Amount Due--}}
{{--                                    </td>--}}
{{--                                    <td colspan="4" class="font-weight-bold text-center">--}}
{{--                                        {{ number_format($total) }}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            </tfoot>--}}
                        </table>

                    </div>
                    {{-- <div class="col-md-3">
                        {{ $penaltyAmount ?? 'N/A' }}
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3">
                        {{ $interestAmount ?? 'N/A' }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
