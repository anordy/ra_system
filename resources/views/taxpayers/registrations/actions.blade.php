
@if($row->checkPendingAmendment() == false)
        <a href="{{ route('taxpayers.enroll-fingerprint', encrypt($row->id)) }}" class="btn btn-outline-primary rounded-0 btn-sm m-1">
            <i class="bi bi-fingerprint mr-1"></i> Enroll Fingerprint
        </a>

        <button class="btn btn-outline-success rounded-0 btn-sm m-1" onclick="Livewire.emit('showModal', 'kyc.kyc-amendment-request-add-modal', '{{ encrypt($row->id) }}')">
            <i class="bi bi-pen mr-1"></i> Amendment Request
        </button>
@else
    <span class="badge badge-warning py-1 px-2 pending-status">
        <i class="bi bi-hourglass-split mr-1"></i>
        Pending Amendment
    </span>
@endif
