<div>
@can('withholding-agents-registration')
        <button class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Edit"
                onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-edit-modal','{{ encrypt($row->id) }}')">
            <i
                    class="bi bi-pencil-square"></i>
        </button>
    @endcan

    @can('withholding-agents-view')
        <a href="{{ route('withholdingAgents.view', encrypt($row->id)) }}" class="btn btn-success btn-sm"
           data-toggle="tooltip"
           data-placement="right" title="View"><i class="bi bi-eye-fill"></i> </a>
    @endcan


    @if ($row->status == \App\Models\WaResponsiblePerson::ACTIVE)
        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="right" title="Deactivate"
                wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="bi bi-unlock-fill"></i></button>
    @else
        <button class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="right" title="Activate"
                wire:click="changeStatus('{{ encrypt($row->id) }}')"><i class="bi bi-lock-fill"></i></button>
    @endif

    <a href="{{ route('withholdingAgents.certificate', encrypt($row->id)) }}" target="_blank" class="btn btn-success btn-sm">
    Certificate of Registration
</a>
</div>