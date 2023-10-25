@if($row->payment_status == \App\Models\Returns\ReturnStatus::COMPLETE)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #35dcb5; color: #0a9e99; font-size: 85%">
        <i class="bi bi bi-check-circle-fill mr-1"></i>
        {{ __('PAID') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::SUBMITTED)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Submitted') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATING)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generating') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATED)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Control Number Generated') }}
    </span>
@elseif($row->payment_status == \App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED)
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-x-circle-fill mr-1"></i>
        {{ __('Control Number Generation Failed') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ ucwords(str_replace('-', ' ', $row->payment_status)) }}
    </span>
@endif
