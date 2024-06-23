@if ($row->status == 'complete')
    <span class="badge badge-success green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID
    </span>
@elseif ($row->status == 'control-number-generated')
    <span class="badge badge-warning green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control Number Generated
    </span>
@elseif ($row->status == 'control-number-generating')
    <span class="badge badge-warning pending-status">
        <i class="fas fa-clock mr-1 "></i>
        Control Number Generating
    </span>
@elseif ($row->status == 'paid-by-debt')
    <span class="badge badge-success green-status">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID BY DEBT
    </span>
@elseif ($row->status == 'control-number-generating-failed')
    <span class="badge badge-warning danger-status">
        <i class="fas fa-exclamation"> </i>
        Control Number Generation Failed
    </span>
@else
    <span class="badge badge-warning pending-status">
        {{ $row->status }}
    </span>
@endif
