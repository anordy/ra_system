
@if($row->checkPendingAmendment() == false)
        <a href="{{ route('taxpayers.enroll-fingerprint', encrypt($row->id)) }}" class="btn btn-outline-primary rounded-0 btn-sm">
            <i class="bi bi-fingerprint mr-1"></i> Enroll Fingerprint
        </a>

        <button class="btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'kyc.kyc-amendment-request-add-modal', {{$row->id}})">
            <i class="bi bi-pen mr-1"></i> Ammendment Request
        </button>
@else
    <span class="badge badge-warning py-1 px-2"
      style="border-radius: 1rem; background: #fed7aa; color: #c2410c; font-size: 85%">
        <i class="bi bi-hourglass-split mr-1"></i>
        Pending Amendment
    </span>
@endif
