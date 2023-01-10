<a href="{{ route('taxpayers.enroll-fingerprint', encrypt($row->id)) }}" class="btn btn-outline-primary rounded-0 btn-sm">
    <i class="bi bi-fingerprint mr-1"></i> Enroll Fingerprint
</a>

<button class="btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'kyc.kyc-amendment-request-add-modal', {{$row->id}})">
    <i class="bi bi-pen mr-1"></i> Ammendment Request
</button>