@if($row->tax_return->penalties == '0' && $row->tax_return->is_nill)
    <span class="badge badge-warning py-1 px-2 green-status">
        <i class="bi bi bi-check-circle-fill mr-1"></i>
        {{ __('NILL RETURN') }}
    </span>

@elseif($row->status == \App\Models\Returns\ReturnStatus::COMPLETE)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi bi-check-circle-fill mr-1"></i>
        {{ __('PAID') }}
    </span>
 @elseif($row->status === \App\Models\Returns\ReturnStatus::COMPLETED_PARTIALLY || $row->status === \App\Models\Returns\ReturnStatus::PAID_PARTIALLY)
    <span class="badge badge-success py-1 px-2 pending-status">
        Partially Paid
    </span>

@elseif($row->status == \App\Models\Returns\ReturnStatus::SUBMITTED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Submitted') }}
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATING)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generating') }}
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generated') }}
    </span>
@elseif($row->status == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        {{ __('Control Number Generation Failed') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ ucwords(str_replace('-', ' ', $row->status)) }}
    </span>
@endif
