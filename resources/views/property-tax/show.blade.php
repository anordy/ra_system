@extends('layouts.master')

@section('title', 'View Property')

@section('content')
    @if($property->status === \App\Enum\PropertyStatus::APPROVED && $property->latestPayment)
        <div class="row mx-1">
            <div class="col-md-12">
                <livewire:property-tax.property-tax-payment :payment="$property->latestPayment" />
            </div>
        </div>
    @endif

    <ul class="nav nav-tabs shadow-sm mb-0">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
               aria-selected="true"> Property Information</a>
        </li>
        @if($property->type == \App\Enum\PropertyTypeStatus::STOREY_BUSINESS || $property->type == \App\Enum\PropertyTypeStatus::RESIDENTIAL_STOREY)
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="storeys-tab" data-toggle="tab" href="#storeys" role="tab" aria-controls="storeys"
                   aria-selected="true"> Storeys and Units</a>
            </li>
        @endif
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="home"
               aria-selected="true"> Payment History</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="approval-tab" data-toggle="tab" href="#approval" role="tab" aria-controls="approval"
               aria-selected="false">Approval History</a>
        </li>
    </ul>

    <div class="tab-content bg-white border shadow-sm" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-uppercase">Property Information</h5>
                </div>
                <div class="card-body">
                    <div class="row m-2 pt-3">
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Status</span>
                            <p class="my-1">
                                @if ($property->status === \App\Enum\PropertyStatus::APPROVED)
                                    <span class="font-weight-bold text-success">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Approved
                                    </span>
                                @elseif($property->status === \App\Enum\PropertyStatus::PENDING)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        Pending
                                    </span>
                                @elseif($property->status === \App\Enum\PropertyStatus::CORRECTION)
                                    <span class="font-weight-bold text-warning">
                                        <i class="bi bi-check-circle-fill mr-1"></i>
                                        On Correction
                                    </span>
                                @else
                                    <span class="font-weight-bold text-info">
                                        <i class="bi bi-clock-history mr-1"></i>
                                        Unknown Status
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Name/Number</span>
                            <p class="my-1">{{ $property->name ?? 'N/A' }}</p>
                        </div>
                        @if($property->house_number)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">House Number</span>
                                <p class="my-1">{{ $property->house_number }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Unit Registration Number</span>
                            <p class="my-1">{{ $property->urn ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Region</span>
                            <p class="my-1">{{ $property->region_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">District</span>
                            <p class="my-1">{{ $property->district_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Ward</span>
                            <p class="my-1">{{ $property->ward_id ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Type</span>
                            <p class="my-1">{{ formatEnum($property->type) ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Size</span>
                            <p class="my-1">{{ $property->size ?? 'N/A' }} sqft</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Property Value</span>
                            <p class="my-1">{{ $property->property_value ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Purchase Value</span>
                            <p class="my-1">{{ $property->purchase_value ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Acquisition Date</span>
                            <p class="my-1">{{ $property->acquisition_date ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Ownership Type</span>
                            <p class="my-1">{{ formatEnum($property->ownership->name) ?? 'N/A' }}</p>
                        </div>
                        @if($property->ownership->name != \App\Enum\PropertyOwnershipTypeStatus::PRIVATE)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Institution Name</span>
                                <p class="my-1">{{ $property->institution_name ?? 'N/A' }}</p>
                            </div>
                        @endif
                        @if ($property->type === \App\Enum\PropertyTypeStatus::HOTEL)
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Hotel Stars</span>
                                <p class="my-1">{{ $property->star->name ?? 'N/A' }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Usage Type</span>
                            <p class="my-1">{{ formatEnum($property->usage_type) ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Registered By</span>
                            <p class="my-1">{{ $property->taxpayer->first_name ?? 'N/A' }}
                                {{ $property->taxpayer->last_name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <span class="font-weight-bold text-uppercase">Date of Registration</span>
                            <p class="my-1">{{ $property->created_at->toFormattedDateString() ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                @if($property->agent)

                    <div class="card rounded-0">
                        <div class="card-header bg-white font-weight-bold">Property Agent</div>
                        <div class="card-body">
                            <div class="row m-2 pt-3">
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Name</span>
                                    <p class="my-1">{{ $property->agent->first_name ?? 'N/A' }} {{ $property->agent->middle_name }} {{ $property->agent->last_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Email</span>
                                    <p class="my-1">{{ $property->agent->email ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Mobile</span>
                                    <p class="my-1">{{ $property->agent->mobile ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <span class="font-weight-bold text-uppercase">Alt Mobile</span>
                                    <p class="my-1">{{ $property->responsible->alt_mobile ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


            @if ($property->type === \App\Enum\PropertyTypeStatus::CONDOMINIUM && $property->unit)
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="text-uppercase">Unit Information</h5>
                        </div>
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Unit Name/Number</span>
                                <p class="my-1">{{ $property->unit->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Usage Type</span>
                                <p class="my-1">{{ formatEnum($property->unit->usage_type) ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Storey Number</span>
                                <p class="my-1">{{ $property->unit->storey->number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">House Number</span>
                                <p class="my-1">{{ $property->unit->house_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Meter Number</span>
                                <p class="my-1">{{ $property->unit->meter_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($property->responsible && $property->responsible->idType->name === \App\Models\IDType::ZANID)
                    <div class="card rounded-0">
                        <div class="card-header bg-white font-weight-bold">ZANID VERIFICATION</div>
                        <div class="card-body">
                            @livewire('property-tax.verification.zanid', ['responsiblePerson' => $property->responsible])
                        </div>
                    </div>
                @endif

                @if ($property->responsible && $property->responsible->idType->name === \App\Models\IDType::TIN)
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header bg-white font-weight-bold">TIN VERIFICATION</div>
                            @livewire('property-tax.verification.tin', ['responsiblePerson' => $property->responsible])
                        </div>
                    </div>
                @endif

                @if($property->ownership->name === \App\Enum\PropertyOwnershipTypeStatus::PRIVATE && $property->responsible)
                    <div class="card-header">
                        <h5 class="text-uppercase">Responsible Person</h5>
                    </div>
                    <div class="card-body">
                        <div class="row m-2 pt-3">
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Name</span>
                                <p class="my-1">{{ $property->responsible->first_name ?? 'N/A' }}
                                    {{ $property->responsible->middle_name }} {{ $property->responsible->last_name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Gender</span>
                                <p class="my-1">{{ $property->responsible->gender ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Date of Birth</span>
                                <p class="my-1">
                                    {{ $property->responsible->date_of_birth ? $property->responsible->date_of_birth->toFormattedDateString() : 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Email</span>
                                <p class="my-1">{{ $property->responsible->email ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Mobile</span>
                                <p class="my-1">{{ $property->responsible->mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">Address</span>
                                <p class="my-1">{{ $property->responsible->address ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ID Type</span>
                                <p class="my-1">{{ $property->responsible->idType->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <span class="font-weight-bold text-uppercase">ID Number</span>
                                <p class="my-1">{{ $property->responsible->id_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            <livewire:property-tax.bill-preview
                    propertyId="{{ encrypt($property->id) }}"></livewire:property-tax.bill-preview>

            <livewire:approval.property-tax-approval-processing modelName="{{ get_class($property) }}"
                                                                modelId="{{ encrypt($property->id) }}"></livewire:approval.property-tax-approval-processing>

        </div>

        <div class="tab-pane fade m-2" id="storeys" role="tabpanel" aria-labelledby="storeys-tab">
            <table class="table table-bordered">
                <thead>
                <th>Storey</th>
                <th>Unit Name</th>
                <th>Purpose</th>
                <th>House Number</th>
                </thead>
                <tbody>
                @foreach($property->storeys as $index => $storey)
                    @foreach($storey->units as $unit)
                        <tr>
                            @if($loop->first)
                                <td rowspan="{{ $storey->units()->count() }}">
                                    # {{ $index + 1 }}
                                </td>
                            @endif
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->usage_type }}</td>
                            <td>{{ $unit->house_number }}</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        </div>


        <div class="tab-pane fade m-2" id="payment" role="tabpanel" aria-labelledby="payment-tab">
            @livewire('property-tax.property-tax-payment-table', ['propertyId' => encrypt($property->id)])
        </div>

        <div class="tab-pane fade m-2" id="approval" role="tabpanel" aria-labelledby="approval-tab">
            <livewire:approval.approval-history-table modelName='{{ \App\Models\PropertyTax\Property::class }}'
                                                      modelId="{{ encrypt($property->id) }}"/>
        </div>
    </div>

@endsection
