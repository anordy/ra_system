<h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
<hr>
<div class="row m-2 pt-3">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Name</span>
        <p class="my-1">{{ $tax_return->business->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Location</span>
        <p class="my-1">{{ $tax_return->location->name ?? 'Head Quarter' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">ZIN No.</span>
        <p class="my-1">{{ $tax_return->location->zin }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Application Step</span>
        <p class="my-1"><span class="badge badge-info">{{ $tax_return->application_step }}</span></p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Tax Type</span>
        <p class="my-1">{{ $tax_return->taxType->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Return Category</span>
        <p class="my-1">{{ $tax_return->return_category }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Application Status</span>
        <p class="my-1"><span class="badge badge-info">{{ $tax_return->application_status }}</span></p>
    </div>
</div>

<div>

    <h6 class="text-uppercase mt-2 ml-2">Debt Payment Figures</h6>
    <hr>
    <div class="row m-2 pt-3">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Principal Amount</span>
            <p class="my-1">{{ $tax_return->currency }}. {{ number_format($tax_return->principal ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Penalty</span>
            <p class="my-1">{{ $tax_return->currency }}. {{ number_format($tax_return->penalty ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Interest</span>
            <p class="my-1">{{ $tax_return->currency }}. {{ number_format($tax_return->interest ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Total Amount</span>
            <p class="my-1">{{ $tax_return->currency }}. {{ number_format($tax_return->total_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
            <p class="my-1">{{ $tax_return->currency }}. {{ number_format($tax_return->outstanding_amount ?? 0, 2) }}</p>
        </div>
        @if ($tax_return->status != 'submitted')
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Payment Status</span>
                <p class="my-1"><span class="badge badge-info">{{ $tax_return->payment_status ?? '' }}</span></p>
            </div>
        @endif
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Payment Due Date</span>
            <p class="my-1">{{ $tax_return->curr_payment_due_date->toFormattedDateString() ?? '' }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Payment Method</span>
            <p class="my-1">{{ $tax_return->payment_method ?? 'N/A' }}</p>
        </div>
    </div>
</div>