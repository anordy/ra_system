<a href="{{ route('taxpayers.taxpayer.show', encrypt($row->id)) }}" class="btn btn-outline-primary rounded-0 btn-sm">
    <i class="bi bi-fingerprint mr-1"></i> View
</a>

<button class="btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'taxpayers.details-amendment-request-add-modal', {{$row->id}})">
    <i class="bi bi-pen mr-1"></i> Ammendment Request
</button>

@if ($row->is_first_login == 1)
    <button class="btn btn-outline-secondary btn-sm" wire:click="resendCredential({{$row->id}})"><i class="fa fa-envelope"></i> Send credentials</button>
@endif
