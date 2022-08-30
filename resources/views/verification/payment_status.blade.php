<div>

    @if ($row->taxReturn != null && $row->taxReturn->status == \App\Models\Returns\ReturnStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid
        </span>
    @elseif ($row->taxReturn != null && $row->taxReturn->status == \App\Models\Returns\ReturnStatus::PAID_BY_DEBT)
        <span class="badge badge-success py-1 px-2"
            style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid by Debt
        </span>
    @else
        <span class="badge badge-danger py-1 px-2"
            style="border-radius: 1rem; background: rgba(53,220,220,0.35); color: #1caecf; font-size: 85%">
            <i class="bi bi-clock-history mr-1"></i>
            Pending
        </span>
    @endif
</div>
