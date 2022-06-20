<div wire:init="verifyUser">
    <div class="tabs">
        <button class="tab-item {{ $selectedStep === 'details' ? 'active' : '' }}" wire:click="changeStep('details')">
            Taxpayer Details
        </button>
        <button class="tab-item {{ $selectedStep === 'biometric' ? 'active' : '' }}" wire:click="changeStep('biometric')">
            Biometric Enrollment
        </button>
    </div>

    @if($selectedStep === 'biometric' && $error)
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($selectedStep === 'details')
        <div class="mt-4">
            <x-taxpayers.details :kyc="$kyc"></x-taxpayers.details>
        </div>
    @elseif($selectedStep === 'biometric')
        @if($verifyingUser)
            <div class="text-center p-5 my-5">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h6 class="mt-3">Verifying user information.</h6>
            </div>
        @endif

        @if($userVerified)
            <div class="mt-4 text-center">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </td>
                            <td>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Left Hand Thumb
                            </td>
                            <td>
                                Right Hand Thumb
                            </td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{ route('taxpayers.verify-user', $kyc->id) }}" class="btn btn-primary rounded-0"><i class="bi bi-check2-all mr-1"></i> Complete Verification</a>
            </div>
        @endif
    @endif
</div>
