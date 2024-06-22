@if ($value === \App\Enum\PBZPaymentStatusEnum::PAID)
    <span class="badge badge-success py-1 px-2">
        Paid
    </span>
@elseif($value === \App\Enum\PBZPaymentStatusEnum::PAID_PARTIALLY)
    <span class="badge badge-warning py-1 px-2">
        Paid Partially
    </span>
@elseif($value === \App\Enum\PBZPaymentStatusEnum::PAID_INCORRECTLY)
    <span class="badge badge-warning py-1 px-2">
        Paid Incorrectly
    </span>
@elseif($value === \App\Enum\PBZPaymentStatusEnum::REVERSED)
    <span class="badge badge-danger py-1 px-2">
        Reversed
    </span>
@elseif($value === \App\Enum\PBZPaymentStatusEnum::FAILED)
    <span class="badge badge-warning py-1 px-2">
        Paid Incorrectly
    </span>
@elseif(!$value)
    <span class="badge badge-secondary py-1 px-2">
        N/A
    </span>
@else
    <span class="badge badge-warning py-1 px-2">
        {{ $value ?? "N/A" }}
    </span>
@endif