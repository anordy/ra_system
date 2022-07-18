{{--<a href="{{ route('taxagents.active-show', $row->id) }}" class="btn btn-outline-primary rounded-0 btn-sm">--}}
{{--    <i class="bi bi-eye mr-1"></i> View--}}
{{--</a>--}}

{{--<button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'tax-agent.tax-agent-request-view',{{$row->id}})"><i class="fa fa-eye"></i> </button>--}}
@if($row->status == 'pending')
    <button class="btn btn-outline-success rounded-0 btn-sm" wire:click="approve({{$row->id}})"><i class="fa fa-check"></i>Process </button>
    <button class="btn btn-outline-danger rounded-0 btn-sm" wire:click="reject({{$row->id}})"><i class="bi bi-x-circle-fill"></i>Reject </button>
@endif

