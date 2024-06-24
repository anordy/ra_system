@if ($row->status === 'complete')
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Complete
    </span>
@elseif($row->status === 'incomplete')
    <span class="badge badge-danger py-1 px-2 draft-status">
        <i class="bi bi-clock-history mr-1"></i>
        Incomplete
    </span>
@endif
