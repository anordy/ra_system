@if($row->payment_status == \App\Models\Returns\ReturnStatus::COMPLETE)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi bi-check-circle-fill mr-1"></i>
        {{ __('PAID') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::SUBMITTED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Submitted') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATING)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generating') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generated') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-x-circle-fill mr-1"></i>
        {{ __('Control Number Generation Failed') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2 green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ ucwords(str_replace('-', ' ', $row->payment_status)) }}
    </span>
@endif
