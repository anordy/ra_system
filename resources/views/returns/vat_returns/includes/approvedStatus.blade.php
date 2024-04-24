@if ($row->status == \App\Models\Returns\ReturnStatus::COMPLETE)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        Complete
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::SUBMITTED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Submitted
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATING)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control number generating
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control number generated
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control number generating failed
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::PAID_BY_DEBT)
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Paid by debt
    </span>
@else
    <span class="badge badge-success py-1 px-2">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ $row->status  }}
    </span>
@endif
