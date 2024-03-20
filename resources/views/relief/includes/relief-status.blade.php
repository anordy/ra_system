@if ($row->status == "approved")
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($row->status == "rejected")
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        Rejected
    </span>
@elseif($row->status == "draft")
    <span class="badge badge-warning py-1 px-2"
        style="border-radius: 1rem; background: #f9c83559; color: #f9c835; font-size: 85%">
        <i class="bi bi-file-text-fill mr-1"></i>
        Draft
    </span>
@elseif($row->status == "pending")
    <span class="badge badge-info py-1 px-2"
        style="border-radius: 1rem; background: #f9c83559; color: #f8bb02; font-size: 85%">
        <i class="bi bi-clock-fill mr-1"></i>
        Pending
    </span>
@endif
