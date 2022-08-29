@can('land-lease-change-agent-status')
    @if ($row->status == 'ACTIVE')
        <a href="{{ route('land-lease.agent.status.change', encrypt(json_encode(['id' => $row->id, 'active' => false]))) }}"
            class="btn btn-outline-warning btn-sm">
            <i class="bi bi-pencil-square mr-1"></i> Deactivate
        </a>
    @elseif($row->status == 'INACTIVE')
        <a href="{{ route('land-lease.agent.status.change', encrypt(json_encode(['id' => $row->id, 'active' => true]))) }}"
            class="btn btn-outline-success btn-sm">
            <i class="bi bi-pencil-square mr-1"></i> Activate
        </a>
    @endif
@endcan
