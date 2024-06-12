<div>
    @if ($row->payment_status != null && $row->status == \App\Enum\BillStatus::COMPLETE)
        <span class="badge badge-success py-1 px-2 custom-payment-box">
            <i class="bi bi-check-circle-fill mr-1"></i>
            Paid
        </span>
    @elseif($row->payment_status == \App\Enum\BillStatus::CN_GENERATED)
        <span class="badge badge-danger py-1 px-2 green-status">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
    @elseif($row->payment_status == \App\Enum\BillStatus::CN_GENERATING)
        <span class="badge badge-danger py-1 px-2 custom-payment-box">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
    @elseif($row->payment_status == \App\Enum\BillStatus::PAID_PARTIALLY)
        <span class="badge badge-danger py-1 px-2 custom-payment-box">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
    @elseif($row->payment_status == \App\Enum\BillStatus::COMPLETED_PARTIALLY)
        <span class="badge badge-danger py-1 px-2 custom-payment-box">
            <i class="bi bi-clock-history mr-1"></i>
            pending
        </span>
    @endif

</div>
