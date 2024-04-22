@if($row->status === \App\Enum\MvrDeRegistrationStatus::PENDING)
    <span class="badge badge-info py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Pending') }}
            </span>
@elseif($row->status === \App\Enum\MvrDeRegistrationStatus::APPROVED)
    <span class="badge badge-success py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('Approved') }}
            </span>
@elseif($row->status === \App\Enum\MvrDeRegistrationStatus::CORRECTION)
    <span class="badge badge-warning py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ __('For Corrections') }}
            </span>
@else
    <span class="badge badge-primary py-1 px-2">
                <i class="bi bi-check-circle-fill mr-1"></i>
                {{ strtoupper($row->status) }}
            </span>
@endif