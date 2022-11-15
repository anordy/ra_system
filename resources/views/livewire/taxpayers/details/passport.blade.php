<div>
    <hr>
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">{{ $kyc->identification->name }} No.</span>
            <p class="my-1">{{ $kyc->id_number }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Permit Number</span>
            <p class="my-1">{{ $kyc->permit_number }}</p>
        </div>
        @if (!$passport)
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

    @if ($passport)
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
                            <td>{{ ucfirst($passport['FirstName']) }}</td>
                            @if ($this->compareProperties($kyc->first_name, $passport['FirstName']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>MIDDLE NAME</th>
                            <td>{{ strtoupper($kyc->middle_name) }}</td>
                            <td>{{ $passport['MiddleName'] }}</td>
                            @if ($this->compareProperties($kyc->middle_name, $passport['MiddleName']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>LAST NAME</th>
                            <td>{{ strtoupper($kyc->last_name) }}</td>
                            <td>{{ $passport['SurName'] }}</td>
                            @if ($this->compareProperties($kyc->last_name, $passport['SurName']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>NATIONALITY</th>
                            <td>{{ strtoupper($kyc->country->nationality) }}</td>
                            <td>{{ $passport['PresentNationality'] }}</td>
                            @if ($this->compareProperties($kyc->country->nationality, $passport['PresentNationality']))
                                <td class="table-succes">{{ $matchesText }}</td>
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
                <p class="my-1">{{ "{$passport['FirstName']} {$passport['MiddleName']} {$passport['SurName']}" }}</p>
            </div>
            @if ($passport['OtherNames'])
                <div class="col-md-4 mb-3">
                    <span class="font-weight-bold text-uppercase">Other Names</span>
                    <p class="my-1">{{ $passport['OtherNames'] }}</p>
                </div>
            @endif
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Date of Birth</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['BirthDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Marital Status</span>
                <p class="my-1">{{ $passport['MaritalStatus'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Present Nationality</span>
                <p class="my-1">{{ $passport['PresentNationality'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Company Name</span>
                <p class="my-1">{{ $passport['CompanyName'] ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Type</span>
                <p class="my-1">{{ $passport['PassportType'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Issued Country</span>
                <p class="my-1">{{ $passport['IssuedCountry'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Issued Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['IssuedDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Expiry Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['ExpiryDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Passport Status</span>
                <p class="my-1">
                    @if (\Carbon\Carbon::now()->greaterThan($passport['ExpiryDate']))
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
                <p class="my-1">{{ $passport['ResidencePermitType'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Issued Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['ResidenceIssuedDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Expire Date</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($passport['ResidenceExpireDate'])->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Residence Permit Status</span>
                <p class="my-1">
                    @if (\Carbon\Carbon::now()->greaterThan($passport['ResidenceExpireDate']))
                        <span class="badge badge-danger">Expired</span>
                    @else
                        <span class="badge badge-success">Active</span>
                    @endif
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-danger ml-1">
                        Reject
                </button>
                <button class="btn btn-success ml-1">
                        Accept
                </button>
            </div>
        </div>
    @endif
</div>
</div>
