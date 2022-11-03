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
                                                @if ($dispute->waiver_category == 'interest')
                                                    disabled
                                                @endif />
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
                                                wire:model="interestPercent" type="number" min=0 max=100
                                                @if ($dispute->waiver_category == 'penalty')
                                                disabled
                                            @endif />
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
                                        {{ number_format($this->penalty, 2) }}
                                    </td>
                                    <td>
                                        Interest Amount
                                    </td>
                                    <td>
                                        {{ number_format($this->interest, 2) }}
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
                                        {{ $this->penalty - $penaltyAmount }}
                                    </td>
                                    <td>
                                        Due Interest Amount
                                    </td>
                                    <td>
                                        {{ $this->interest - $interestAmount }}
                                    </td>

                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="card-footer text-center">
                                        Principal amount
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{ number_format($this->principal) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="card-footer text-center">
                                        Total waived amount
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{ $penaltyAmountDue + $interestAmountDue }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="card-footer text-center">
                                        Total Amount Due
                                    </td>
                                    <td colspan="4" class="font-weight-bold text-center">
                                        {{ number_format($this->total) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
