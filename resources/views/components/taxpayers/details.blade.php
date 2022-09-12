<div class="row my-2">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Full Name</span>
        <p class="my-1">{{ "{$kyc->first_name} {$kyc->middle_name} {$kyc->last_name}" }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Email Address</span>
        <p class="my-1">{{ $kyc->email }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Mobile</span>
        <p class="my-1">{{ $kyc->mobile }}</p>
    </div>
    @if($kyc->alt_mobile)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
            <p class="my-1">{{ $kyc->alt_mobile }}</p>
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Nationality</span>
        <p class="my-1">{{ $kyc->country->nationality }}</p>
    </div>

</div>
<hr />
<div class="row">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">{{ $kyc->identification->name }} No.</span>
        <p class="my-1">{{ $kyc->id_number }}</p>
    </div>
    @if($kyc->work_permit)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Residence Permit</span>
            <p class="my-1">{{ $kyc->work_permit }}</p>
        </div>
    @endif
    @if($kyc->residence_permit)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Work Permit</span>
            <p class="my-1">{{ $kyc->residence_permit }}</p>
        </div>
    @endif
</div>
<hr />
<div class="row">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Location</span>
        <p class="my-1">{{ $kyc->region->name }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Street</span>
        <p class="my-1">{{ $kyc->street }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Physical Address</span>
        <p class="my-1">{{ $kyc->physical_address }}</p>
    </div>
</div>