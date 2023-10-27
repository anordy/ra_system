<div>
    <div class="row row m-2">
        @if ($responsiblePerson->id_number)
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">ZANID No.</span>
                <p class="my-1">{{ $responsiblePerson->id_number }}</p>
            </div>
        @endif

        @if (empty($responsiblePerson->id_verified_at))
            <div class="col-md-4 mb-3">
                <button wire:click="validateZanID" wire:loading.attr="disabled" class="btn btn-info btn-sm">
                    <div wire:loading wire:target="validateZanID">
                        <div class="spinner-border mr-1 spinner-border-sm text-light" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>Verify Zan ID Data
                </button>
            </div>
        @else
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">ZANID Verified At</span>
                <p class="my-1">{{ \Carbon\Carbon::parse($responsiblePerson->id_verified_at)->format('d M Y H:i:s') }}</p>
            </div>
        @endif
    </div>

    @if ($is_verified_triggered && $zanid_data != null)
        @if ($zanid_data['data'] !== null)
        <hr>
        <div class="row row m-2">
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">ZAN ID NO</span>
                <p class="my-1">{{ $responsiblePerson->id_number }}</p>
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
                <span class="font-weight-bold text-uppercase">Email</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_EMAILS'] }}</p>
            </div>
            <div class="col-md-4 mb-3">
                <span class="font-weight-bold text-uppercase">Address</span>
                <p class="my-1">{{ $zanid_data['data']['PRSN_RES_ADDRESS'] }}</p>
            </div>
        </div>
        <hr>
        @else
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="row row m-2">
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
            <div class="row row m-2">
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
