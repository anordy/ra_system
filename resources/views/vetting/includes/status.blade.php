@if ($row->vetting_status == \App\Enum\VettingStatus::VETTED)
    <span class="p-2 badge badge-success"
        style="border-radius: 1rem; background: #72DC3559; color: #319e0a; font-size: 100%; padding:3%">
        <i class="bi bi-check-circle-fill mr-1"></i>
        Vetted
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTION)
    <span class="p-2 badge badge-warning"
        style="border-radius: 1rem; background: #f40f0b59; color: #5e3e3e; font-size: 80%; padding:3%">
        <i class="fas fa-exclamation"> </i>
        On Correction
    </span>
@elseif ($row->vetting_status == \App\Enum\VettingStatus::CORRECTED)
    <span class="p-2 badge badge-warning"
        style="border-radius: 1rem; background: #f40f0b59; color: #5e3e3e; font-size: 80%; padding:3%">
        <i class="fas fa-exclamation"> </i>
        Corrected
    </span>
@else
    <span class="p-2 badge badge-warning"
        style="border-radius: 1rem; background: #d1dc3559; color: #474704; font-size: 100%; padding:3%">
        {{ $row->vetting_status }}
    </span>
@endif
