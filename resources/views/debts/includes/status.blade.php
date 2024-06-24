@if ($row->payment_status == 'complete')
    <span class="badge badge-success">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID
    </span>
@elseif ($row->payment_status == 'control-number-generated')
    <span class="badge badge-warning">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control Number Generated
    </span>
@elseif ($row->payment_status == 'control-number-generating')
    <span class="badge badge-warning">
        <i class="fas fa-clock mr-1 "></i>
        Control Number Generating
    </span>
@elseif ($row->payment_status == 'control-number-generating-failed')
    <span class="badge badge-warning">
        <i class="fas fa-exclamation"> </i>
        Control Number Generation Failed
    </span>
@else
    <span class="badge badge-warning">
        {{ $row->payment_status }}
    </span>
@endif
