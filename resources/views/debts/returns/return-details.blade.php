<h6 class="text-uppercase mt-2 ml-2">Original Return Figure</h6>
<hr>
<div class="row m-2 pt-3">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Principal Amount</span>
        <p class="my-1">{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->total_amount_due, 2) }}
        </p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Penalty</span>
        <p class="my-1">{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->penalty, 2) }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Interest</span>
        <p class="my-1">{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->interest, 2) }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Total Amount</span>
        <p class="my-1">{{ $tax_return->return->currency }}. {{ number_format($tax_return->return->total_amount_due_with_penalties, 2) }}</p>
    </div>
    <div class="col-md-3 mb-3">
        <span class="font-weight-bold text-uppercase">Return Month</span>
        <p class="my-1">{{$tax_return->return->financialMonth->name}} {{ $tax_return->return->financialYear->code }}</p>
    </div>
</div>