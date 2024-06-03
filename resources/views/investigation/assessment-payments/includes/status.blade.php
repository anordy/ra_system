@if ($value == "complete")
    <span class="badge badge-success">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __("PAID") }}
    </span>
@elseif ($value == "control-number-generated")
    <span class="badge badge-warning">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __("Control Number Generated") }}
    </span>
@elseif ($value == "control-number-generating")
    <span class="badge badge-warning">
        <i class="fas fa-clock mr-1"></i>
        {{ __("Control Number Generating") }}
    </span>
@elseif ($value == "paid-by-debt")
    <span class="badge badge-success">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __("PAID BY DEBT") }}
    </span>
@elseif ($value == "control-number-generating-failed")
    <span class="badge badge-danger">
        <i class="fas fa-exclamation"> </i>
        {{ __("Control Number Generation Failed") }}
    </span>
@else
    <span class="badge badge-warning">
        {{ $value }}
    </span>
@endif
