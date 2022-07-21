<div wire:init="verifyUser">
    <div class="tabs">
        <button class="tab-item {{ $selectedStep === 'details' ? 'active' : '' }}" wire:click="changeStep('details')">
            Taxpayer Details
        </button>
        <button class="tab-item {{ $selectedStep === 'biometric' ? 'active' : '' }}"
            wire:click="changeStep('biometric')">
            Biometric Enrollment
        </button>
    </div>

    @if ($selectedStep === 'biometric' && $error)
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($selectedStep === 'details')
        <div class="mt-4">
            <x-taxpayers.details :kyc="$kyc"></x-taxpayers.details>
        </div>
    @elseif($selectedStep === 'biometric')
        @if ($verifyingUser)
            <div class="text-center p-5 my-5">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <h6 class="mt-3">Verifying user information.</h6>
            </div>
        @endif

        @if ($userVerified)
            <div class="mt-4 text-center">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td colspan="5"><b>Left Hand</b></td>
                        </tr>
                        <tr>
                            <th width="20%" align="center">
                                Little <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i><br>
                                @if (!$this->enrolled('left', 'little'))
                                    <button class="btn btn-outline-info btn-sm"
                                        onclick="Livewire.emit('showModal', 'taxpayers.bio-enroll-modal', {{$kyc->id}},'left','little')">Click
                                        To Enroll</button>
                                @else
                                    <span class="badge badge-success">Enrolled</span>
                                @endif
                            </th>
                            <th width="20%" align="center">
                                Ring <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i><br>
                                @if (!$this->enrolled('left', 'ring'))
                                <button class="btn btn-outline-info btn-sm"
                                    onclick="Livewire.emit('showModal', 'taxpayers.bio-enroll-modal',{{ $kyc->id }},'left','ring')">Click
                                    To Enroll</button>
                            @else
                                <span class="badge badge-success">Enrolled</span>
                            @endif
                            </th>
                            <th width="20%" align="center">
                                Middle <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Index <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Thumb <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                        <tr>
                            <td colspan="5"><b>Right Hand</b></td>
                        </tr>
                        <tr>
                            <th width="20%" align="center">
                                Little <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Ring <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Middle <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Index <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                            <th width="20%" align="center">
                                Thumb <br>
                                <i class="bi bi-fingerprint" style="font-size: 50px"></i>
                            </th>
                        </tr>

                    </tbody>
                </table>

                <a href="{{ route('taxpayers.verify-user', $kyc->id) }}" class="btn btn-primary rounded-0"><i
                        class="bi bi-check2-all mr-1"></i> Complete Verification</a>
            </div>
        @endif
    @endif
</div>
