<div>
    @if ($value == \App\Models\Returns\ReturnStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #10b981; color: #065f46; font-size: 85%">
            <i class="bi bi bi-x-circle-fill mr-1"></i>
            Complete
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::SUBMITTED)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Submitted
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATING)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #fbbf24; color: #d97706; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generating
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATED)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #60a5fa; color: #1d4ed8; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generated
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #f87171; color: #b91c1c; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Control number generating failed
        </span>
    @elseif($value == \App\Models\Returns\ReturnStatus::PAID_BY_DEBT)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #f43f5e; color: #be123c; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid by debt
        </span>
    @else
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #818cf8; color: #4338ca; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid Partially
        </span>
    @endif

</div>
