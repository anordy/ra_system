<div class="row my-2">
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Full Name</span>
        <p class="my-1">{{ "{$kyc->first_name} {$kyc->middle_name} {$kyc->last_name}" }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Email Address</span>
        <p class="my-1">{{ $kyc->email ?? 'N/A' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Mobile</span>
        <p class="my-1">{{ $kyc->mobile }}</p>
    </div>
    @if ($kyc->alt_mobile)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Alternative Mobile</span>
            <p class="my-1">{{ $kyc->alt_mobile }}</p>
        </div>
    @endif
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Nationality</span>
        <p class="my-1">{{ $kyc->country->nationality }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Region</span>
        <p class="my-1">{{ $kyc->region->name ?? 'N/A'}}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">District</span>
        <p class="my-1">{{ $kyc->district->name ?? 'N/A'}}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Ward</span>
        <p class="my-1">{{ $kyc->ward->name ?? 'N/A'}}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Street</span>
        <p class="my-1">{{ $kyc->street->name ?? 'N/A' }}</p>
    </div>
    <div class="col-md-4 mb-3">
        <span class="font-weight-bold text-uppercase">Physical Address</span>
        <p class="my-1">{{ $kyc->physical_address }}</p>
    </div>
    @if ($kyc->nida_no)
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">NIDA No</span>
            <p class="my-1">{{ $kyc->nida_no }}</p>
        </div>     
    @endif
</div>


@if ($kyc->identification->name == \App\Models\IDType::ZANID or $kyc->identification->name == \App\Models\IDType::NIDA_ZANID)
    <livewire:taxpayers.details.zanid :kyc="$kyc" />
@endif

@if ($kyc->identification->name == \App\Models\IDType::PASSPORT)
    <livewire:taxpayers.details.passport :kyc="$kyc" />
@endif
