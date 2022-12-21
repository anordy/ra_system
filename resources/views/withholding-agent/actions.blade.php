<div>
@can('withholding-agents-registration')
    <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Edit"
        onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-edit-modal','{{ encrypt($row->id) }}')"><i
            class="fa fa-edit"></i>
    </button>
@endcan

@can('withholding-agents-view')
    <a href="{{ route('withholdingAgents.view', encrypt($row->id)) }}" class="btn btn-success btn-sm" data-toggle="tooltip"
        data-placement="right" title="View"><i class="fa fa-eye"></i> </a>
@endcan


@if ($row->status == 'active')
        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Deactivate"
            wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="fa fa-unlock"></i> </button>
@else
        <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right" title="Activate"
            wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="fa fa-lock"></i> </button>
@endif

<a href="{{ route('withholdingAgents.certificate', encrypt($row->id)) }}" target="_blank" class="btn btn-success btn-sm">
    Certificate of Registration
</a>
</div>