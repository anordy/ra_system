@if ($row->status === \App\Models\BusinessStatus::APPROVED)
    {{-- Also check if business update status is pending, rejected or on correction --}}
    @if ($row->businessUpdate)
        @if ($row->businessUpdate->status === \App\Models\BusinessStatus::PENDING)
            <span class="badge badge-danger py-1 px-2 draft-status">
                <i class="bi bi-clock-history mr-1"></i>
                Pending Changes
            </span>
        @elseif($row->businessUpdate->status === \App\Models\BusinessStatus::CORRECTION)
            <span class="badge badge-danger py-1 px-2 danger-status">
                <i class="bi bi-record-circle mr-1"></i>
                Correction
            </span>
        @elseif($row->businessUpdate->status === \App\Models\BusinessStatus::REJECTED)
            <span class="badge badge-danger py-1 px-2 danger-status">
                <i class="bi bi-record-circle mr-1"></i>
                Rejected Changes
            </span>
        @elseif($row->businessUpdate->status === \App\Models\BusinessStatus::APPROVED)
            <span class="badge badge-success py-1 px-2 green-status">
                <i class="bi bi-check-circle-fill mr-1"></i>
                Approved
            </span>
        @endif
    @else
        <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Approved
        </span>
    @endif
@elseif($row->status === \App\Models\BusinessStatus::PENDING)
    <span class="badge badge-danger py-1 px-2 pending-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Models\BusinessStatus::DRAFT)
    <span class="badge badge-danger py-1 px-2 draft-status">
        <i class="bi bi-pencil-square mr-1"></i>
        Draft
    </span>
@elseif($row->status === \App\Models\BusinessStatus::CORRECTION)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Correction
    </span>
@elseif($row->status === \App\Models\BusinessStatus::TEMP_CLOSED)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Temporary Closed
    </span>
@elseif($row->status === \App\Models\BusinessStatus::DEREGISTERED)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        Deregistered
    </span>
@endif
