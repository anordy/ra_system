<div>
    @if ($value == \App\Models\Returns\ReturnStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi bi-x-circle-fill mr-1"></i>
            Complete
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::SUBMITTED)
        <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Submitted
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATING)
        <span class="badge badge-success py-1 px-2 pending-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generating
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATED)
        <span class="badge badge-success py-1 px-2 draft-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generated
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
        <span class="badge badge-success py-1 px-2 danger-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generating failed
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::PAID_BY_DEBT)
        <span class="badge badge-success py-1 px-2  danger-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid by debt
        </span>
    @else
        <span class="badge badge-success py-1 px-2 draft-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid Partially
        </span>
    @endif

</div>
