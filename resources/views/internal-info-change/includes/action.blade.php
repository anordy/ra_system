@if ($row->status === \App\Models\BusinessStatus::PENDING)
    @php
        $instace = $row->pinstances->last();
        if ($instace == null) {
            return;
        }
        $operators = json_decode($instace->operators, true);
    @endphp

    @if ($instace->operator_type == 'staff')
        @if (in_array(auth()->id(), $operators))
            <a href="{{ route('business.internal-info-change.show', encrypt($id)) }}" class="btn btn-outline-info btn-sm"
                data-toggle="tooltip" data-placement="right" title="View">View & Approve</a>
        @endif
    @elseif($instace->operator_type == 'role')
        @if (in_array(auth()->user()->role->id, $operators))
            <a href="{{ route('business.internal-info-change.show', encrypt($id)) }}" class="btn btn-outline-info btn-sm"
                data-toggle="tooltip" data-placement="right" title="View">View & Approve</a>
        @endif
    @else
        <a href="{{ route('business.internal-info-change.show', encrypt($id)) }}" class="btn btn-outline-info btn-sm"
            data-toggle="tooltip" data-placement="right" title="View">View</a>
    @endif
@else
    <a href="{{ route('business.internal-info-change.show', encrypt($id)) }}" class="btn btn-outline-info btn-sm"
        data-toggle="tooltip" data-placement="right" title="View">View</a>
@endif
