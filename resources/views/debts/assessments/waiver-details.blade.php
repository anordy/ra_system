@if ($assessment->waiver)
    <h6 class="text-uppercase mt-2 ml-2">Waiver Details</h6>
    <hr>
    <div class="row m-2 pt-3">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Waiver Type</span>
            <p class="my-1">{{ $assessment->waiver->category }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Waiver Status</span>
            <p class="my-1"><span class="badge badge-info">{{ $assessment->waiver->status }}</span></p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Principal Amount</span>
            <p class="my-1">{{ $assessment->currency }}. {{ number_format($assessment->principal_amount, 2) }}
            </p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Waived Penalty Percentage</span>
            <p class="my-1">{{ number_format($assessment->waiver->penalty_rate, 2)  }} % of {{ number_format($assessment->penalty_amount, 2) }}
            </p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Waived Interest Percentage</span>
            <p class="my-1"> {{ number_format($assessment->waiver->interest_rate, 2)  }} % of {{ number_format($assessment->interest_amount, 2) }}
            </p>
        </div>
 
    </div>
@else
    <h6 class="text-uppercase text-center mt-2 ml-2">No Waiver</h6>
@endif