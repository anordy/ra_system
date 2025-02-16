@if($row->status === \App\Enum\MvrReorderStatus::PENDING)
    <span class="badge badge-info py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
@elseif($row->status === \App\Enum\MvrReorderStatus::STATUS_REGISTERED)
    <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Registered') }}
            </span>
        </span>
        @elseif($row->status === \App\Enum\MvrReorderStatus::APPROVED)
            <span class="badge badge-success py-1 px-2">
                        <i class="bi bi-check-circle-fill mr-1"></i>
                        {{ __('Approved') }}
                    </span>
@elseif($row->status === \App\Enum\MvrReorderStatus::CORRECTION)
    <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('For Corrections') }}
            </span>
@else
    <span class="badge badge-primary py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ $row->status }}
            </span>
@endif
