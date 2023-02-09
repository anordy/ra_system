<div>
    <hr>
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Passport No.</span>
            <p class="my-1">{{ $kyc->passport_no }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Permit Number</span>
            <p class="my-1">{{ $kyc->permit_number }}</p>
        </div>
        @if (empty($kyc->passport_verified_at))
            <div class="col-md-4 mb-3">
                <button wire:click="validatePassport" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                    <div wire:loading wire:target="validatePassport">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Verify Data
                </button>
            </div>
        @endif
    </div>

    @if ($is_verified_triggered && $passport['data'] != null)
        <hr>
        <div class="row">
            <div class="col-md-11">
                <table class="table table-striped table-sm">
                    <label class="font-weight-bold text-uppercase">Immigration Data Verification</label>
                    <thead>
                        <th style="width: 37%">Property</th>
                        <th style="width: 37%">Provided Data</th>
                        <th style="width: 18%">Immigration Data</th>
                        <th style="width: 8%">Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>FIRST NAME</th>
                            <td>{{ strtoupper($kyc->first_name) }}</td>
                            <td>{{ ucfirst($passport['data']['FirstName']) }}</td>
                            @if ($this->compareProperties($kyc->first_name, $passport['data']['FirstName']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>MIDDLE NAME</th>
                            <td>{{ strtoupper($kyc->middle_name) }}</td>
                            <td>{{ $passport['data']['MiddleName'] }}</td>
                            @if ($this->compareProperties($kyc->middle_name, $passport['data']['MiddleName']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>LAST NAME</th>
                            <td>{{ strtoupper($kyc->last_name) }}</td>
                            <td>{{ $passport['data']['SurName'] }}</td>
                            @if ($this->compareProperties($kyc->last_name, $passport['data']['SurName']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>NATIONALITY</th>
                            <td>{{ strtoupper($kyc->country->nationality) }}</td>
                            <td>{{ $passport['data']['PresentNationality'] }}</td>
                            @if ($this->compareProperties($kyc->country->nationality, $passport['data']['PresentNationality']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Full Name</span>
                <p class="my-1">{{ "{$passport['data']['FirstName']} {$passport['data']['MiddleName']} {$passport['data']['SurName']}" }}</p>
            </div>
            @if ($passport['data']['OtherNames'])
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Other Names</span>
                    <p class="my-1">{{ $passport['data']['OtherNames'] }}</p>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Date of Birth</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['data']['BirthDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Marital Status</span>
                <p class="my-1">{{ $passport['data']['MaritalStatus'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Present Nationality</span>
                <p class="my-1">{{ $passport['data']['PresentNationality'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Company Name</span>
                <p class="my-1">{{ $passport['data']['CompanyName'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Type</span>
                <p class="my-1">{{ $passport['data']['PassportType'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Issued Country</span>
                <p class="my-1">{{ $passport['data']['IssuedCountry'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Issued Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['data']['IssuedDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Expiry Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['data']['ExpiryDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Status</span>
                <p class="my-1">
                    @if (\Carbon\Carbon::now()->greaterThan($passport['data']['ExpiryDate']))
                        <span class="badge badge-danger">Expired</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Permit Type</span>
                <p class="my-1">{{ $passport['data']['ResidencePermitType'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Issued Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['data']['ResidenceIssuedDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Expire Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['data']['ResidenceExpireDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Permit Status</span>
                <p class="my-1">
                    @if (\Carbon\Carbon::now()->greaterThan($passport['data']['ResidenceExpireDate']))
                        <span class="badge badge-danger">Expired</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </p>
            </div>
        </div>
        <hr>
        @if (empty($kyc->authorities_verified_at))
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-danger ml-2" wire:click="rejectIncomingData" wire:loading.attr="disabled">
                    <i class="bi bi-x-lg ml-1" wire:loading.remove wire:target="rejectIncomingData"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="rejectIncomingData"></i>
                        Reject
                </button>
                <button class="btn btn-primary rounded-0 btn-sm ml-2" onclick="Livewire.emit('showModal', 'kyc.kyc-amendment-request-add-modal', '{{ encrypt($kyc->id) }}')">
                    <i class="bi bi-pen mr-1"></i> Amend Kyc Details
                </button>
                <button class="btn btn-success ml-2" wire:click="acceptIncomingData" wire:loading.attr="disabled">
                    <i class="bi bi-check-lg ml-1" wire:loading.remove wire:target="acceptIncomingData"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="acceptIncomingData"></i>
                        Accept
                </button>
            </div>
        </div>
        @endif
    @elseif($is_verified_triggered && $passport['data'] == null)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="row">
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Error Code</span>
                <p class="my-1">{{ $passport['code'] ?? '' }}</p>
            </div>
            <div class="col-md-4">
                <span class="font-weight-bold text-uppercase">Message</span>
                <p class="my-1">{{ $passport['msg'] ?? '' }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
