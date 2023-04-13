@if ($row->vetting_status == \App\Enum\VettingStatus::VETTED)
    <span class="badge badge-success py-1 px-2"
          style="border-radius: 1rem; background: #bef264; color: #4d7c0f; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ __('Vetted') }}
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTION)
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: #38bdf8; color: #0369a1; font-size: 85%">
        <i class="bi bi-clock-history mr-1"></i>
        {{ __('On Correction') }}
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTED)
    <span class="badge badge-danger py-1 px-2"
          style="border-radius: 1rem; background: #fcd34d; color: #d97706; font-size: 85%">
        <i class="bi bi-record-circle mr-1"></i>
        {{ __('Corrected') }}
    </span>
@else
    <span class="badge badge-success py-1 px-2"
          style="border-radius: 1rem; background: #404040; color: #FFFFFF; font-size: 85%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        {{ $row->vetting_status }}
    </span>
@endif

