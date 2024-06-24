@if ($offence->status  == 'paid')
    <span class="badge badge-success p-2  opacity-75" style="opacity: 75%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        PAID
    </span>
@elseif ($offence->status  == 'control-number-generated')
    <span class="badge badge-warning p-2 bg-opacity-25" style="opacity: 75%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Control Number Generated
    </span>
@elseif ($offence->status  == 'control-number-generating')
    <span class="badge badge-warning p-2 bg-opacity-25" style="opacity: 75%">
        <i class="fas fa-clock mr-1 "></i>
        Control Number Generating
    </span>
@elseif ($offence->status  == 'control-number-generating-failed')
    <span class="badge badge-danger p-2 bg-opacity-25" style="opacity: 75%">
        <i class="fas fa-exclamation"> </i>
        Control Number Generation Failed
    </span>

@else
    <span class="badge badge-warning p-2 bg-opacity-25 " style="opacity: 75%">
        {{ $offence->status    }}
    </span>
@endif
