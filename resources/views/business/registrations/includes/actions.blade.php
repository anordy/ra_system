@if($row->status === \App\Models\BusinessStatus::PENDING)
    @php
        $instace = $row->pinstances->last();
        if ($instace == null) {
            return;
        }
        $operators = json_decode($instace->operators, true);
    @endphp

    @if ($instace->operator_type == 'staff')
        @if (in_array(auth()->id(), $operators))
            <a href="{{ route('business.registrations.approval', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye-fill mr-1"></i> View & Approve
            </a>
        @endif
    @elseif($instace->operator_type == 'role')
        @if (in_array(auth()->user()->role->id, $operators))
            <a href="{{ route('business.registrations.approval', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-eye-fill mr-1"></i> View & Approve
            </a>
        @endif
    @else
        <a href="{{ route('business.registrations.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-eye-fill mr-1"></i> View
        </a>
    @endif
@else
    <a href="{{ route('business.registrations.show', encrypt($row->id)) }}" class="btn btn-outline-primary btn-sm">
        <i class="bi bi-eye-fill mr-1"></i> View
    </a>
@endif

@if(!$row->headquarter->vfms_associated_at)
    @can('vfms-znumber-verification')
        <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'business.vfms.fetch-business-unit-data-modal', '{{  encrypt($row->id) }}', '{{ true }}')">
            <i class="bi bi-pen mr-1"></i> Z-NUmber Verification
        </button>
    @endcan
@else
    @can('vfms-business-unit-update')
        <button class="m-1 btn btn-outline-success rounded-0 btn-sm" onclick="Livewire.emit('showModal', 'business.vfms.update-business-unit-details', '{{  encrypt($row->id) }}', '{{ true }}')">
            <i class="bi bi-gear-wide-connected mr-1"></i> Update Business Units
        </button>
    @endcan
@endcan