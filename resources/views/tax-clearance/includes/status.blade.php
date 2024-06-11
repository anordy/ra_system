@if ($value === 'requested')
    <span class="badge badge-warning py-1 px-2 text-capitalize pending-status">
        {{ $value }}
    </span>
@elseif ($value === 'rejected')
    <span class="badge badge-danger py-1 px-2 text-capitalize danger-status">
        {{ $value }}
    </span>
@elseif ($value === 'approved')
    <span class="badge badge-success py-1 px-2 text-capitalize green-status">
        {{ $value }}
    </span>
@endif