@if ($row->status === \App\Models\BusinessStatus::APPROVED)
    <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                Active
            </span>
@elseif($row->status === \App\Models\BusinessStatus::PENDING)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('Pending') }}
    </span>
@elseif($row->status === \App\Models\BusinessStatus::DEREGISTERED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('De-registered') }}
    </span>
@elseif($row->status === \App\Models\BusinessStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('Rejected') }}
    </span>
@else
    <span class="badge badge-danger py-1 px-2">
        <i class="bi bi-record-circle mr-1"></i>
        Unknown Status
    </span>
@endif
