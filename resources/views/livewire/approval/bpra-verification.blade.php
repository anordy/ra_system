<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business No.</span>
            <p class="my-1">{{ $business->reg_no ?? 'N/A' }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $business->name }}</p>
        </div>
        @if ($business->bpra_verification_status === \App\Models\BusinessStatus::PENDING)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Action</span>
                <p class="my-1">
                    <button wire:click="validateBPRANumber" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                        <div wire:loading wire:target="validateBPRANumber">
                            <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>Verify Data From BPRA
                    </button>
                </p>
            </div>
        @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                @if ($business->bpra_verification_status === \App\Models\BusinessStatus::PBRA_UNVERIFIED)
                    <p class="my-1">
                        <span class="badge badge-danger py-1 px-2 text-capitalize pending-status">
                            <i class="bi bi-record-circle mr-1"></i>
                            {{ $business->bpra_verification_status }}
                        </span>
                    </p>
                @elseif($business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED)
                    <p class="my-1">
                        <span class="badge badge-success py-1 px-2 green-status">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Verification Successful
                        </span>
                    </p>
                @endif
            </div>
        @endif

    </div>

    @if ($bpraResponse)
        @if ($business->bpra_verification_status === \App\Models\BusinessStatus::PENDING)
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-sm">
                        <label class="font-weight-bold text-uppercase">BPRA Data Verification</label>
                        <thead>
                            <th>Property</th>
                            <th>Provided Data</th>
                            <th>BPRA Data</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Business No</th>
                                <td>{{ strtoupper($business->reg_no) }}</td>
                                <td>{{ $bpraResponse['businessData']['reg_number'] ? ucfirst($bpraResponse['businessData']['reg_number']) : 'No data found' }}
                                </td>
                                @if ($this->compareProperties($business->reg_no, $bpraResponse['businessData']['reg_number']))
                                    <td class="table-success">{{ $matchesText }}</td>
                                @else
                                    <td class="table-danger">{{ $notValidText }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Business Name</th>
                                <td>{{ strtoupper($business->name) }}</td>
                                <td>{{ $bpraResponse['businessData']['business_name'] ? ucfirst($bpraResponse['businessData']['business_name']) : 'No data found' }}
                                </td>
                                @if ($this->compareProperties($business->name, $bpraResponse['businessData']['business_name']))
                                    <td class="table-success">{{ $matchesText }}</td>
                                @else
                                    <td class="table-danger">{{ $notValidText }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ strtoupper('255' . substr($business->mobile, 1)) }}</td>
                                <td>{{ $bpraResponse['businessData']['mob_phone'] ? ucfirst($bpraResponse['businessData']['mob_phone']) : 'No data found' }}
                                </td>
                                @if ($this->compareProperties('255' . substr($business->mobile, 1), $bpraResponse['businessData']['mob_phone']))
                                    <td class="table-success">{{ $matchesText }}</td>
                                @else
                                    <td class="table-danger">{{ $notValidText }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $business->email }}</td>
                                <td>{{ $bpraResponse['businessData']['email'] ? ucfirst($bpraResponse['businessData']['email']) : 'No data found' }}
                                </td>
                                @if ($this->compareProperties($business->email, $bpraResponse['businessData']['email']))
                                    <td class="table-success">{{ $matchesText }}</td>
                                @else
                                    <td class="table-danger">{{ $notValidText }}</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Registration Date</th>
                                <td></td>
                                <td>{{ $bpraResponse['businessData']['reg_date'] ? ucfirst($bpraResponse['businessData']['reg_date']) : 'No data found' }}
                                </td>
                                <td class="table-secondary"></td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="card-body mt-0 p-2">
                        <ul class="nav nav-tabs shadow-sm mb-0">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="directors-tab" data-toggle="tab" href="#directors"
                                    role="tab" aria-controls="directors" aria-selected="true">Directors</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="shareholders-tab" data-toggle="tab" href="#shareholders"
                                    role="tab" aria-controls="shareholders" aria-selected="false">Shareholders</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="shares_distribution-tab" data-toggle="tab"
                                    href="#shares_distribution" role="tab" aria-controls="shares_distribution"
                                    aria-selected="false">
                                    Shares & Distribution
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content bg-white border shadow-sm" id="myTabContent">
                            <div class="tab-pane fade show active" id="directors" role="tabpanel"
                                aria-labelledby="directors-tab">
                                <div class="row m-1 p-3">
                                    <table class="table table-striped table-sm">
                                        <label class="font-weight-bold text-uppercase mt-2">Directors</label>
                                        <thead>
                                            <th >Name</th>
                                            <th >Phone</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Location</th>
                                        </thead>
                                        <tbody>
                                            @if ($bpraResponse['directors'])
                                                @foreach ($bpraResponse['directors'] as $director)
                                                    <tr>
                                                        <td class="">
                                                            {{ $director['first_name'] }}
                                                            {{ $director['middle_name'] }}
                                                            {{ $director['last_name'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $director['mob_phone'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $director['email'] }}
                                                        </td>
                                                        <td class="">
                                                            @if (substr($director['gender'], 3) == 'M')
                                                                MALE
                                                            @elseif (substr($director['gender'], 3) == 'F')
                                                                FEMALE
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="">
                                                            {{ $director['city_name'] }}
                                                            <div>
                                                                {{ $director['first_line'] }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No data!
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="shareholders" role="tabpanel"
                                aria-labelledby="shareholders-tab">
                                <div class="row m-1 p-3">
                                    <table class="table table-striped table-sm">
                                        <label class="font-weight-bold text-uppercase mt-2">Shareholders</label>
                                        <thead>
                                            <th >Name</th>
                                            <th >Phone</th>
                                            <th>Email</th>
                                            <th>Gender</th>
                                            <th>Location</th>
                                        </thead>
                                        <tbody>
                                            @if (count($bpraResponse['shareHolders']))

                                                @foreach ($bpraResponse['shareHolders'] as $shareholder)
                                                    <tr>
                                                        <td class="">
                                                            {{ $shareholder['entity_name'] ? $shareholder['entity_name'] : $shareholder['first_name'] . ' ' . $shareholder['middle_name'] . ' ' . $shareholder['last_name'] ?? 'N/A' }}
                                                        </td>
                                                        <td class="">
                                                            {{ $shareholder['mob_phone'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $shareholder['email'] }}
                                                        </td>
                                                        <td class="">
                                                            @if (substr($shareholder['gender'], 3) == 'M')
                                                                MALE
                                                            @elseif (substr($shareholder['gender'], 3) == 'F')
                                                                FEMALE
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        
                                                        <td class="">
                                                            @if ($shareholder['city_name'])
                                                                {{ $shareholder['city_name'] }}
                                                                <div>
                                                                    {{ $shareholder['first_line'] }}
                                                                </div>
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No data!
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="shares_distribution" role="tabpanel"
                                aria-labelledby="shares_distribution-tab">
                                <div class="row m-1 p-3">
                                    <table class="table table-striped table-sm">
                                        <label class="font-weight-bold text-uppercase mt-2">Shares &
                                            Distribution</label>
                                        <thead>
                                            <th>Ower Name</th>
                                            <th>No Of Shares</th>
                                            <th>Currency</th>
                                            <th>Shares Taken</th>
                                            <th>Shares Paid</th>
                                        </thead>
                                        <tbody>
                                            @if ($bpraResponse['listShareHolderShares'])
                                                @foreach ($bpraResponse['listShareHolderShares'] as $listShareHolderShare)
                                                    <tr>
                                                        <td class="">
                                                            {{ $listShareHolderShare['shareholder_name'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $listShareHolderShare['number_of_shares'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $listShareHolderShare['currency'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $listShareHolderShare['number_of_shares_taken'] }}
                                                        </td>
                                                        <td class="">
                                                            {{ $listShareHolderShare['number_of_shares_paid'] }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">
                                                        No data!
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="py-1">
                        <div class="mb-1">
                            <small class="font-light font-size-small">
                                <mark><i class="bi bi-lightbulb-fill"></i></mark> : <mark>Continue with existing
                                    provided data won't update any data<mark>
                            </small>
                        </div>
                        <div>
                            <small class="text-danger font-light">
                                <mark><i class="bi bi-lightbulb-fill"></i></mark> : <mark>When Confirm Bpra Data button
                                    is clicked, the stakeholders, directors, and shares details will be saved</mark>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer p-2 m-0">
                        <button wire:click="continueWithProvidedData()" wire:loading.attr="disabled"
                            class="btn btn-primary">
                            <div wire:loading wire:target="confirm">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>Continue With Provided Data
                        </button>
                        <button wire:click="confirm()" wire:loading.attr="disabled" class="btn btn-success">
                            <div wire:loading wire:target="confirm">
                                <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>Confirm BPRA Data
                        </button>
                    </div>

                </div>

            </div>
            <hr>
        @endif
    @endif



</div>
