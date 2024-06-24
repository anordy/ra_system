@if ($value == \App\Enum\PaymentStatus::PAID)
    <span class="badge badge-success py-1 px-2 text-uppercase">
            <i class="bi bi-check-circle-fill"></i>
            Paid
        </span>
@elseif($value == \App\Enum\PaymentStatus::CN_GENERATED)
    <span class="badge badge-success py-1 px-2 text-uppercase">
            <i class="bi bi-clock-history"></i>
            Control No. Generated
        </span>
@elseif($value == \App\Enum\PaymentStatus::PENDING)
    <span class="badge badge-info py-1 px-2 text-uppercase">
            <i class="bi bi-clock-history"></i>
            Pending
        </span>
@elseif($value == \App\Enum\PaymentStatus::FAILED)
    <span class="badge badge-danger py-1 px-2 text-uppercase">
            <i class="bi bi-x-circle-fill"></i>
            Failed
        </span>
@elseif($value == \App\Enum\PaymentStatus::CANCELLED)
    <span class="badge badge-danger py-1 px-2 text-uppercase">
            <i class="bi bi-x-circle-fill"></i>
            Cancelled
        </span>
@elseif($value == \App\Enum\PaymentStatus::PARTIALLY)
    <span class="badge badge-info py-1 px-2 text-uppercase">
            <i class="bi bi-circle-half"></i>
            Paid Partially
        </span>
@elseif($value)
    <span class="badge badge-secondary py-1 px-2 text-uppercase">
        {{ $value }}
    </span>
@endif