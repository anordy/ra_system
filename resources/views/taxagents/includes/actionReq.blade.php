<button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'tax-agent.tax-agent-request-view',{{$row->id}})"><i class="fa fa-eye"></i> </button>
@if($row->status == 'pending')
    <button class="btn btn-success btn-sm" wire:click="approve({{$row->id}})"><i class="fa fa-check"></i> </button>
    <button class="btn btn-danger btn-sm" wire:click="reject({{$row->id}})"><i class="bi bi-x-circle-fill"></i> </button>
@endif

