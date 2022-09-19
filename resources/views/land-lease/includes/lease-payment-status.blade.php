@if ($row->status === \App\Enum\LeaseStatus::IN_ADVANCE_PAYMENT)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Paid In Advance
    </span>
@elseif ($row->status === \App\Enum\LeaseStatus::ON_TIME_PAYMENT)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Paid On Time
    </span>
@elseif ($row->status === \App\Enum\LeaseStatus::LATE_PAYMENT)
    <span class="badge badge-success py-1 px-2"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Paid Late
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::CN_GENERATING)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Control Number Generating
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::CN_GENERATED)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Control Number Generated
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::CN_GENERATION_FAILED)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        Control Number Generating Failed
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::PAID_PARTIALLY)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
        <i class="bi bi-pencil-square mr-1"></i>
        Paid Partially
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::PENDING)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(220,181,53,0.35); color: #cfa51c; font-size: 85%">
        <i class="bi bi-pencil-square mr-1"></i>
        Pending
    </span>
@elseif($row->status === \App\Enum\LeaseStatus::DEBT)
    <span class="badge badge-danger py-1 px-2"
        style="border-radius: 1rem; background: rgba(220,53,53,0.35); color: #cf1c1c; font-size: 85%">
        <i class="bi bi-record-circle mr-1"></i>
        Debt
    </span>
@endif
