@if ($value === \App\Models\BusinessStatus::DRAFT)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Rejected
    </span>
@elseif($value === \App\Models\BusinessStatus::PENDING)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #37382a59; color: #d1d87259; font-size: 85%">
        <i class="bi bi-clock mr-1"></i>
        Pending
    </span>
@elseif ($value === \App\Models\BusinessStatus::APPROVED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Approved
    </span>
@elseif($value === \App\Models\BusinessStatus::CORRECTION)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Correction
    </span>
@elseif($value === \App\Models\BusinessStatus::TEMP_CLOSED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Temp Closed
    </span>
@elseif($value === \App\Models\BusinessStatus::DEREGISTERED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Deregistered
    </span>
@elseif($value === \App\Models\BusinessStatus::REJECTED)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #dc354559; color: #cf1c2d; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Regected
    </span>
@endif

