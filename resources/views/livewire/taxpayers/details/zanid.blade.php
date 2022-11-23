<div>
    <hr>
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">{{ $kyc->identification->name }} No.</span>
            <p class="my-1">{{ $kyc->id_number }}</p>
        </div>
        @if (empty($kyc->authorities_verified_at))
            <div class="col-md-4 mb-3">
                <button wire:click="validateZanID" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                    <div wire:loading wire:target="validateZanID">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Verify Data
                </button>
            </div>
        @endif
    </div>

    @if ($zanid_data)
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
                            <td>{{ ucfirst($zanid_data['PRSN_FIRST_NAME']) }}</td>
                            @if ($this->compareProperties($kyc->first_name, $zanid_data['PRSN_FIRST_NAME']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        {{-- <tr>
                            <th>MIDDLE NAME</th>
                            <td>{{ strtoupper($kyc->middle_name) }}</td>
                            <td>{{ $zanid_data['PRSN_MIDDLE_NAME'] }}</td>
                            @if ($this->compareProperties($kyc->middle_name, $zanid_data['PRSN_MIDLE_NAME']))
                                <td class="table-succes">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr> --}}
                        <tr>
                            <th>LAST NAME</th>
                            <td>{{ strtoupper($kyc->last_name) }}</td>
                            <td>{{ $zanid_data['PRSN_LAST_NAME'] }}</td>
                            @if ($this->compareProperties($kyc->last_name, $zanid_data['PRSN_LAST_NAME']))
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
                <p class="my-1">{{ "{$zanid_data['PRSN_FIRST_NAME']}  {$zanid_data['PRSN_LAST_NAME']}" }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Date of Birth</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($zanid_data['PRSN_BIRTH_DATE'])->format('d/m/Y') }}</p>
            </div>
        </div>
        <hr>
        @if (empty($kyc->authorities_verified_at))
        <div class="row">
            <div class="col-md-12 d-flex justify-content-end">
                <button class="btn btn-danger ml-1" wire:click="rejectIncomingData" wire:loading.attr="disabled">
                    <i class="bi bi-x-lg ml-1" wire:loading.remove wire:target="rejectIncomingData"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="rejectIncomingData"></i>
                        Reject
                </button>
                <button class="btn btn-success ml-1" wire:click="acceptIncomingData" wire:loading.attr="disabled">
                    <i class="bi bi-check-lg ml-1" wire:loading.remove wire:target="acceptIncomingData"></i>
                    <i class="spinner-border spinner-border-sm ml-1" role="status" wire:loading
                        wire:target="acceptIncomingData"></i>
                        Accept
                </button>
            </div>
        </div>
        @endif
    @endif
</div>
</div>
