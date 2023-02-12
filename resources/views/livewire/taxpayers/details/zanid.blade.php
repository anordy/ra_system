<div>
    <hr>
    <div class="row">
        @if ($kyc->zanid_no)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">ZANID No.</span>
                <p class="my-1">{{ $kyc->zanid_no }}</p>
            </div>
        @endif
        @if ($kyc->nida_no)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">NIDA No.</span>
                <p class="my-1">{{ $kyc->nida_no }}</p>
            </div>
        @endif

        @if (empty($kyc->zanid_verified_at))
            <div class="col-md-4 mb-3">
                <button wire:click="validateZanID" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                    <div wire:loading wire:target="validateZanID">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Verify Zan ID Data
                </button>
            </div>
        @endif
    </div>

    @if ($is_verified_triggered && $zanid_data != null)
        @if ($zanid_data['data'] !== null)
        <hr>
        <div class="row">
            <div class="col-md-11">
                <table class="table table-striped table-sm">
                    <label class="font-weight-bold text-uppercase">Zan ID Data Verification</label>
                    <thead>
                        <th style="width: 37%">Property</th>
                        <th style="width: 37%">Provided Data</th>
                        <th style="width: 18%">Zan ID Data</th>
                        <th style="width: 8%">Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>FIRST NAME</th>
                            <td>{{ strtoupper($kyc->first_name) }}</td>
                            <td>{{ ucfirst($zanid_data['data']['PRSN_FIRST_NAME']) }}</td>
                            @if ($this->compareProperties($kyc->first_name, $zanid_data['data']['PRSN_FIRST_NAME']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>MIDDLE NAME</th>
                            <td>{{ strtoupper($kyc->middle_name) }}</td>
                            <td>{{ ucfirst(($zanid_data['data']['PRSN_MIDLE_NAME']) ?? 'N/A') }}</td>
                            @if ($this->compareProperties($kyc->middle_name, $zanid_data['data']['PRSN_MIDLE_NAME']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>LAST NAME</th>
                            <td>{{ strtoupper($kyc->last_name) }}</td>
                            <td>{{ $zanid_data['data']['PRSN_LAST_NAME'] }}</td>
                            @if ($this->compareProperties($kyc->last_name, $zanid_data['data']['PRSN_LAST_NAME']))
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
                <span class="font-weight-bold text-uppercase">ZAN ID NO</span>
                <p class="my-1">{{ $kyc->zanid_no }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Full Name</span>
                <p class="my-1">
                    {{ "{$zanid_data['data']['PRSN_FIRST_NAME']}  {$zanid_data['data']['PRSN_LAST_NAME']}" }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Gender</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_SEX'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Date of Birth</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($zanid_data['data']['PRSN_BIRTH_DATE'])->format('d/m/Y') }}
                </p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">District</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_RES_DISTRICT'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Ward</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_RES_WARD'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Village</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_RES_TOWN_VILLAGE'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">House No</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_RES_HOUSE_PLOT'] }}</p>
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
        @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="row">
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Error Code</span>
                    <p class="my-1">{{ $zanid_data['code'] ?? '500' }}</p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Message</span>
                    <p class="my-1">{{ $zanid_data['msg'] ?? 'Something went wrong' }}</p>
                </div>
            </div>
        </div>
        @endif
    @endif

    @if($is_verified_triggered && $zanid_data == null)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="row">
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Error Code</span>
                    <p class="my-1">{{ $zanid_data['code'] ?? '500' }}</p>
                </div>
                <div class="col-md-4">
                    <span class="font-weight-bold text-uppercase">Message</span>
                    <p class="my-1">{{ $zanid_data['msg'] ?? 'Something went wrong' }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
