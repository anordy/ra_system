@if ($row->status == \App\Enum\BillStatus::COMPLETE)
    <span class="badge badge-success">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('PAID') }}
    </span>
@elseif ($row->status == \App\Enum\BillStatus::CN_GENERATED)
    <span class="badge badge-warning">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generated') }}
    </span>
@elseif ($row->status == \App\Enum\BillStatus::CN_GENERATING)
    <span class="badge badge-warning">
        <i class="fas fa-clock mr-1 "></i>
        {{ __('Control Number Generating') }}
    </span>
@elseif ($row->status == \App\Enum\BillStatus::CN_GENERATION_FAILED)
    <span class="badge badge-warning">
        <i class="fas fa-exclamation"> </i>
        {{ __('Control Number Generation Failed') }}
    </span>
@else
    <span class="badge badge-warning ">
        {{ $row->status }}
    </span>
@endif
