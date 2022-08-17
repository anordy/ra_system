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
                                                @if ($debt_waiver->category == 'interest')
                                                    disabled
                                                @endif
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
                                                @if ($debt_waiver->category == 'penalty')
                                                    disabled
                                                @endif
                                                 />
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
                                        {{ number_format($this->debt->penalty, 2) }}
                                    </td>
                                    <td>
                                        Interest Amount
                                    </td>
                                    <td>
                                        {{ number_format($this->debt->interest, 2) }}
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        Waived Penalty Amount
                                    </td>
                                    <td>
                                        {{ number_format($penaltyAmount, 2) ?? 'N/A' }}
                                    </td>
                                    <td>
                                        Waived Interest Amount
                                    </td>
                                    <td>
                                        {{ number_format($interestAmount, 2) ?? 'N/A' }}
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        Due Penalty Amount
                                    </td>
                                    <td>
                                        {{ number_format($this->debt->penalty- $penaltyAmount, 2) }}
                                    </td>
                                    <td>
                                        Due Interest Amount
                                    </td>
                                    <td>
                                        {{ number_format($this->debt->interest - $interestAmount, 2) }}
                                    </td>

                                </tr>


                                <tr>
                                    <td class="card-footer text-center">
                                        Principal Amount
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{ number_format($debt->principal_amount) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="card-footer text-center">
                                        Total Amount Due
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{ number_format($total) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
