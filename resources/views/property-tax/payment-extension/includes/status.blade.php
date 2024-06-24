@if($row->status == \App\Enum\PaymentExtensionStatus::APPROVED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi bi-check-circle-fill mr-1"></i>
        {{ __('Approved') }}
    </span>
@elseif($row->status == \App\Enum\PaymentExtensionStatus::PENDING)
    <span class="badge badge-warning py-1 px-2 draft-status">
        <i class="bi bi-clock-history mr-1"></i>
        Pending
    </span>
@elseif($row->status == \App\Enum\PaymentExtensionStatus::REJECTED)
    <span class="badge badge-danger py-1 px-2 danger-status">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('Rejected') }}
    </span>
@else
    <span class="badge badge-secondary py-1 px-2 pending-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ ucwords(str_replace('-', ' ', $row->status)) }}
    </span>
@endif
