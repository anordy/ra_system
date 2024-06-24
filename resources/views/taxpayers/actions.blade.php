<a href="{{ route('taxpayers.taxpayer.show', encrypt($row->id)) }}" class="m-1 btn btn-outline-primary rounded-0 btn-sm">
    <i class="bi bi-fingerprint mr-1"></i> View
</a>

@if($row->checkPendingAmendment() == false)
    <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'taxpayers.details-amendment-request-add-modal', '{{  encrypt($row->id) }}')">
        <i class="bi bi-pen mr-1"></i> Amendment Request
    </button>
@else
    <span class="m-1 badge badge-warning py-1 px-2">
        <i class="bi bi-hourglass-split mr-1"></i>
        Pending Amendment
    </span>
@endif

@if ($row->is_first_login == 1)
    <button class="m-1 btn btn-outline-secondary btn-sm" wire:click="resendCredential({{$row->id}})"><i class="bi bi-envelope-fill"></i> Send credentials</button>
@endif

@if ($row->failed_verification == 1)
    <button class="m-1 btn btn-outline-secondary btn-sm" wire:click="openVerifyAccountModal({{$row->id}})"><i class="bi bi-shield-check"></i> Re-verify</button>
@endif