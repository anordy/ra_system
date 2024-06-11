@if($row->is_verified == 0)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-clock-history mr-1"></i>
        Not Verified
    </span>

@else
        <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Verified
    </span>

@endif