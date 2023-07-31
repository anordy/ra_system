@if ($row->status != 'filled')
    @php
        $instace = $row->pinstances->last();
        if ($instace == null) {
            return;
        }
        $operators = json_decode($instace->operators, true);
    @endphp

    @if ($instace->operator_type == 'staff')
        @if (in_array(auth()->id(), $operators))
            <a href="{{ route('petroleum.certificateOfQuantity.show', encrypt($value)) }}"
                class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye-fill mr-1"></i> View & Approve
            </a>
        @endif
    @elseif($instace->operator_type == 'role')
        @if (in_array(auth()->user()->role->id, $operators))
            <a href="{{ route('petroleum.certificateOfQuantity.show', encrypt($value)) }}"
                class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye-fill mr-1"></i> View & Approve
            </a>
        @endif
    @else
        <a href="{{ route('petroleum.certificateOfQuantity.show', encrypt($value)) }}"
            class="btn btn-outline-primary btn-sm">
            <i class="bi bi-eye-fill mr-1"></i> View
        </a>
        <a href="{{ route('petroleum.certificateOfQuantity.certificate', encrypt($value)) }}"
            class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="right" title="View">Print
            Certificate</a>
    @endif
@else
    <a href="{{ route('petroleum.certificateOfQuantity.show', encrypt($value)) }}"
        class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
    <a href="{{ route('petroleum.certificateOfQuantity.certificate', encrypt($value)) }}"
        class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="right" title="View">Print
        Certificate</a>
@endif
