<h6 class="text-uppercase mt-2 ml-2">Debt Details</h6>
<hr>
<div class="row m-2 pt-3">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Name</span>
        <p class="my-1">{{ $assessment->business->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Business Location</span>
        <p class="my-1">{{ $assessment->location->name ?? 'Head Quarter' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">ZIN No.</span>
        <p class="my-1">{{ $assessment->location->zin ??'' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Assessment Step</span>
        <p class="my-1"><span class="badge badge-info">{{ $assessment->assessment_step }}</span></p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Tax Type</span>
        <p class="my-1">{{ $assessment->taxType->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Application Status</span>
        <p class="my-1"><span class="badge badge-info">{{ $assessment->app_status }}</span></p>
    </div>
</div>

<div>

    <h6 class="text-uppercase mt-2 ml-2">Assessment Debt Payment Figures</h6>
    <hr>
    <div class="row m-2 pt-3">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Principal Amount</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->principal_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Penalty</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->penalty_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Interest</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->interest_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Total Amount</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->total_amount ?? 0, 2) }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Outstanding Amount</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->outstanding_amount ?? 0, 2) }}</p>
        </div>
        @if ($assessment->status != 'submitted')
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Payment Status</span>
                <p class="my-1"><span class="badge badge-info">{{ $assessment->payment_status ?? '' }}</span></p>
            </div>
        @endif
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Payment Due Date</span>
            <p class="my-1">{{ $assessment->payment_due_date ?? '' }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Payment Method</span>
            <p class="my-1">{{ $assessment->payment_method ?? 'N/A' }}</p>
        </div>
    </div>
</div>