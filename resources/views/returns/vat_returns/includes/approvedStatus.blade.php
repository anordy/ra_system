@if($row->status == 'complete')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #35dcb5; color: #0a9e99; font-size: 85%">
        <i class="bi bi bi-x-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::COMPLETE}}
    </span>

@elseif($row->status == 'submitted')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::SUBMITTED}}
    </span>
@elseif($row->status == 'control-number-generating')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATING}}
    </span>
@elseif($row->status == 'control-number-generated')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATED}}
    </span>
@elseif($row->status == 'control-number-generating-failed')
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::CN_GENERATION_FAILED}}
    </span>
@else
    <span class="badge badge-success py-1 px-2"  style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{\App\Models\Returns\ReturnStatus::PAID_PARTIALLY}}
    </span>
@endif
