<div>

    @if ($row->taxReturn != null && $row->taxReturn->status == \App\Models\Returns\ReturnStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid
        </span>
    @elseif ($row->taxReturn != null && $row->taxReturn->status == \App\Models\Returns\ReturnStatus::PAID_BY_DEBT)
        <span class="badge badge-success py-1 px-2 green-status">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid by Debt
        </span>
    @else
        <span class="badge badge-danger py-1 px-2 pending-status">
            <i class="bi bi-clock-history mr-1"></i>
            Pending
        </span>
    @endif
</div>
