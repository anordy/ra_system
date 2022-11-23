<h6 class="text-uppercase mt-2 ml-2">Business Information</h6>
<hr>
<div class="row m-2 pt-3">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Name</span>
        <p class="my-1">{{ $tax_return->business->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Tax Identification No. (TIN)</span>
        <p class="my-1">{{ $tax_return->business->tin }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Reg. No.</span>
        <p class="my-1">{{ $tax_return->business->reg_no }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">ZIN</span>
        <p class="my-1">{{ $tax_return->location->zin }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Owner Designation</span>
        <p class="my-1">{{ $tax_return->business->owner_designation }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Mobile</span>
        <p class="my-1">{{ $tax_return->business->mobile }}</p>
    </div>
    @if ($tax_return->business->alt_mobile)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Alternative Mobile No.</span>
            <p class="my-1">{{ $tax_return->business->alt_mobile }}</p>
        </div>
    @endif
    @if ($tax_return->business->email_address)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Email Address</span>
            <p class="my-1">{{ $tax_return->business->email }}</p>
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Place of Business</span>
        <p class="my-1">{{ $tax_return->business->place_of_business }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Physical Address</span>
        <p class="my-1">{{ $tax_return->business->physical_address }}</p>
    </div>
</div>

@include('debts.returns.return-details', ['tax_return' => $tax_return])

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