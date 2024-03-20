@if($row->status == 'pending')
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>

@elseif($row->status == 'approved')
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@else
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        Rejected
    </span>
@endif