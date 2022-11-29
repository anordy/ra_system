<div class="col-md-12">
    <div class="row">
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business No.</span>
            <p class="my-1">{{ $business->reg_no }}</p>
        </div>
        <div class="col-md-4 mb-3">
            <span class="font-weight-bold text-uppercase">Business Name</span>
            <p class="my-1">{{ $business->name }}</p>
        </div>
        {{-- @if (empty($business->authorities_verified_at)) --}}
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
        {{-- @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Status</span>
                @if ($business->bpra_verification_status === \App\Models\BusinessStatus::REJECTED)
                    <p class="my-1">
                        <span class="badge badge-danger py-1 px-2"
                            style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
                            <i class="bi bi-record-circle mr-1"></i>
                            Invalid Data
                        </span>
                    </p>
                @elseif($business->bpra_verification_status === \App\Models\BusinessStatus::APPROVED)
                    <p class="my-1">
                        <span class="badge badge-success py-1 px-2"
                            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
                            <i class="bi bi-check-circle-fill mr-1"></i>
                            Verification Successful
                        </span>
                    </p>
                @endif
            </div>

        @endif --}}

    </div>

    @if ($bpraResponse)
        <hr>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-sm">
                    <label class="font-weight-bold text-uppercase">BPRA Data Verification</label>
                    <thead>
                        <th style="width: 18%">Property</th>
                        <th style="width: 37%">Provided Data</th>
                        <th style="width: 37%">BPRA Data</th>
                        <th style="width: 8%">Status</th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Business No</th>
                            <td>{{ strtoupper($business->reg_no) }}</td>
                            <td>{{ $bpraResponse['reg_number'] != '' ? ucfirst($bpraResponse['reg_number']) : 'No data found' }}</td>
                            @if ($this->compareProperties($business->reg_no, $bpraResponse['reg_number']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Business Name</th>
                            <td>{{ strtoupper($business->name) }}</td>
                            <td>{{ $bpraResponse['business_name'] != '' ? ucfirst($bpraResponse['business_name']) : 'No data found' }}</td>
                            @if ($this->compareProperties($business->name, $bpraResponse['business_name']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ strtoupper('255' . substr($business->mobile, 1)) }}</td>
                            <td>{{ $bpraResponse['mob_phone'] != '' ? ucfirst($bpraResponse['mob_phone']) : 'No data found' }}</td>
                            @if ($this->compareProperties( '255' . substr($business->mobile, 1), $bpraResponse['mob_phone']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $business->email }}</td>
                            <td>{{ $bpraResponse['email'] != '' ? ucfirst($bpraResponse['email']) : 'No data found' }}</td>
                            @if ($this->compareProperties($business->email, $bpraResponse['email']))
                                <td class="table-success">{{ $matchesText }}</td>
                            @else
                                <td class="table-danger">{{ $notValidText }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th>Registration Date</th>
                            <td></td>
                            <td>{{ $bpraResponse['reg_date'] != '' ? ucfirst($bpraResponse['reg_date']) : 'No data found' }}</td>
                            <td class="table-secondary"></td>
                    </tbody>
                </table>
            </div>
        </div>
        <br>

    @endif
</div>
